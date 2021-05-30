<?php
/**
 * Telegram communication serice
 *
 * @author Serhii Shkrabak
 * @package Library\Telegram
 */
namespace Model\Services;
class Telegram
{
	use \Library\Shared;
	use \Library\Uniroad;

	private ?Int $chat;
	private Int $reload = 0;

	public function send(String $text, Int $chat = 0, Array $keyboard = [], Int $reload = 0, Bool $inline = true) {
		if ($reload)
			$this->reload = $reload;
		if (!$chat)
			$chat = $this->chat;
		$method = 'sendMessage';
		$reply = '';
		if (!empty($keyboard)) {
			$reply = '&reply_markup=';
			$reply .= json_encode([
				$inline ? 'inline_keyboard' : 'keyboard' => $keyboard,
				'one_time_keyboard' => true,
				'resize_keyboard' => true
			]);
		}
		if ($this->reload) {
			$method = 'editMessageText';
		}
		$text = urlencode($text);
		file_get_contents("https://api.telegram.org/bot{$this->key}/$method?parse_mode=markdown&chat_id=$chat&text=$text" . ($this->reload ? "&message_id={$this->reload}" : '') . $reply );
	}

	public function alert(String $body = '') {
		$this->send("Батут зломався: $body", $this->emergency);
	}

	public function setChat(Int $id):self {
		$this->chat = $id;
		return $this;
	}

	private function getReply(String $code):string {
		$db = $this->db;
		$reply = $db -> select(['Messages' => []])
					-> where(['Messages'=> ['code' => $code]])
					-> many();
		if (empty($reply))
			$reply = $this->getReply('unknown');
		else {
			$reply = $reply[mt_rand (0,count($reply)-1)];
		}
		return $reply['text'];
	}

	private function getContext(\Model\Entities\User $user, String $text, ?String $entrypoint = null):string {
		$message = \Model\Entities\Message::search(id: $user->message, limit: 1);
		$fields = $message->getChildren();
		$input = $user->input;
		$response = '';
		$full = false;

		foreach ( $fields as $field ) {
			if (!isset($input[$field->code])) {
				if (!$full) {
					$input[$field->code] = $text;
					$full = true;
				}
				else {
					$response = ( $field->title ? '*' . $field->title . "*\n\n" : '') . $field->text;
					break;
				}
			}
		}

		$update = ['input' => $input];
		if (count($input) == count($fields)) {
			$update['message'] = null;
			if($message->service) {
				$service = \Model\Entities\Service::search(id: $message->service, limit: 1);
				$service = new \Model\Entities\Service($input['s-title'], $input['s-description'], user: $user->id,
					token: $this->generateToken(32), signature: $this->generateToken(32));
				$service->save();
				$response = "✅ *ЗГЕНЕРОВАНО КЛЮЧІ*\n\nВикористовуйте для інтеграції наступні ключі:\n\n🔐 *Токен:* " . $service->token
						. "\nТокен призначено для верифікації запитів до Вашого сервісу\n\n🖇 *Підпис:* " . $service->signature
						. "\nПідпис призначено для виконання запитів з Вашого сервісу до Єдиних інформаційних систем\n";
			}
		}
		$user->set($update);
		return $response;
	}

	private function callbackMessage(\stdClass $sentence, \Model\Entities\User $user):void {
		$inline = true;
		$keyboard = [];
		$recipient = $sentence->to == $user->guid
			? $user
			: \Model\Entities\User::search(guid: $sentence->to, limit: 1);
		$value = property_exists($sentence, 'value') ? $sentence->value : 'text lost';
		if (property_exists($sentence, 'keyboard')) {
			if (property_exists($sentence->keyboard, 'inline'))
				$inline = $sentence->keyboard->inline;
			foreach ($sentence->keyboard->buttons as $row) {
				$keys = [];
				foreach ($row as $button) {
					$key = [
						'text' => $button->title,
						'callback_data' => json_encode([
							'id' => $button->id,
							'type' => 1,
							'reload' => property_exists($button, 'reload')
						])
					];
					if (property_exists($button, 'request'))
						$key["request_{$button->request}"] = true;

					$keys[] = $key;
				}
				$keyboard[] = $keys;
			}
		}
		if ($recipient)
			$this->send($value, chat: $recipient->chat, keyboard: $keyboard, inline: $inline);
	}

	private function callbackContext(\stdClass $sentence, \Model\Entities\User $user):void {
		$user->set(['service' => $sentence->set]);
		$this->send('Контекст змінено', chat: $user->chat);
	}

	private function workout(\Model\Entities\User $user, String $text = '', ?Int $code = null, Int $phone = 0):void {//отправкка запроса
		$this->reload = 0;

		if ($user->service) {
			if ($code) {
				$config = [
					'type' => 'click',
					'code' => $code
				];
			} else
				if ($phone) {
					$config = [
						'type' => 'contact',
						'value' => $phone
					];
				} else {
					$config = [
						'type' => 'message',
						'value' => $text,
						'from' => $user->guid
					];
				}

			$answer = $this->uni()->get($user->service, $config)->one();//get(название сервиса(proxy,stats),аргументы которые хотим передать методу)

			if ($this->uni()->state)
				throw new \Exception("Service {$user->service} returned state " . $this->uni()->state, 10);

			if ($answer && property_exists($answer, 'callback')) {
				foreach ($answer->callback as $sentence) {
					if (property_exists($sentence, 'type'))
						if (method_exists($this, "callback{$sentence->type}")) {
							$method = [$this, "callback{$sentence->type}"];
							$method($sentence, $user);
						}
				}
			}
		}
	}

	private function local(Int $type, \Model\Entities\User $user, String $text, ?Int $code):void {
		$response = '';
		switch ($type) {
			case 4: // Встановлюється сервіс
				$message = \Model\Entities\Message::search(id: $code, limit: 1);
				$user->set([
					'service' => $message->service
				]);
				$response = $this->workout($user, '/start');
				break;
			case 2: // Вводиться форма
				$message = \Model\Entities\Message::search(id: $code, limit: 1);
				if ($entrypoint) {
					$user->set([
						'message' => $message->id,
						'input' => []
					]);
					$field = $message->getChildren(1);
					$response = ( $field->title ? '*' . $field->title . "*\n\n" : '') . $field->text;
				}
				break;
			default:
				if ($user->message)
					$response = $this->getContext($user, $text);
				else {
					$message = ($type == 1)
						? \Model\Entities\Message::search(id: $code, limit: 1)
						: \Model\Entities\Message::search(entrypoint: isset($code) ? $code : $text, limit: 1);

					if ($message) {
						$response = ($message->title ? '*' . $message->title . "*\n\n" : '') . $message->text;
						$keyboard = $message->getKeyboard();

					} else {
						$response = $this->getReply('unknown');
					}
				}
		}
		if ($response)
			$this->send($response, keyboard: $keyboard);
	}

	public function process(Array $entrypoint, String $terminal = '', Bool $edited = false):void {
		if (!isset($this->chat))
			$this->setChat($entrypoint['chat']['id']);

		$db = $this->db;
		$response = '';
		$phone = 0;
		$text = isset($entrypoint['text']) ? $entrypoint['text'] : '';
		$keyboard = [];
		$this->reload = 0;
		$type = 0;
		$code = 0;

		$user = \Model\Entities\User::search(chat: $this->chat, limit: 1);

		if (!$user) {
			$user = new \Model\Entities\User(chat: $this->chat);
			$user->save();
		}
		$GLOBALS['user'] = $user;

		if (isset($entrypoint['contact'])) {
			$phone = $entrypoint['contact']['phone_number'];
		} else {

			if ($terminal) {
				$command = json_decode($terminal, true);
				$code = $command['id'];
				$type = $command['type'];
				if ($command['reload']) {
					$this->reload = $entrypoint['message_id'];
				}
				$text = '';

			}

			if ($text == '/start' && $user->service)
				$user->set(['service' => null]);
		}
		if ($user->service) {
			$this->workout($user, $text, $code, $phone);
		}
		else
			$this->local($type, $user, $text, $code);
	}

	public function __construct(private String $key, private Int $emergency) {
		$this->db = $this->getDB();
	}

}