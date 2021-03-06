<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* OneDriveActivityUserDetail File
* PHP version 7
*
* @category  Library
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
namespace Beta\Microsoft\Graph\Model;

/**
* OneDriveActivityUserDetail class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class OneDriveActivityUserDetail extends Entity
{
    /**
    * Gets the assignedProducts
    *
    * @return string|null The assignedProducts
    */
    public function getAssignedProducts()
    {
        if (array_key_exists("assignedProducts", $this->_propDict)) {
            return $this->_propDict["assignedProducts"];
        } else {
            return null;
        }
    }
    
    /**
    * Sets the assignedProducts
    *
    * @param string $val The assignedProducts
    *
    * @return OneDriveActivityUserDetail
    */
    public function setAssignedProducts($val)
    {
        $this->_propDict["assignedProducts"] = $val;
        return $this;
    }
    
    /**
    * Gets the deletedDate
    *
    * @return \DateTime|null The deletedDate
    */
    public function getDeletedDate()
    {
        if (array_key_exists("deletedDate", $this->_propDict)) {
            if (is_a($this->_propDict["deletedDate"], "\DateTime") || is_null($this->_propDict["deletedDate"])) {
                return $this->_propDict["deletedDate"];
            } else {
                $this->_propDict["deletedDate"] = new \DateTime($this->_propDict["deletedDate"]);
                return $this->_propDict["deletedDate"];
            }
        }
        return null;
    }
    
    /**
    * Sets the deletedDate
    *
    * @param \DateTime $val The deletedDate
    *
    * @return OneDriveActivityUserDetail
    */
    public function setDeletedDate($val)
    {
        $this->_propDict["deletedDate"] = $val;
        return $this;
    }
    
    /**
    * Gets the isDeleted
    *
    * @return bool|null The isDeleted
    */
    public function getIsDeleted()
    {
        if (array_key_exists("isDeleted", $this->_propDict)) {
            return $this->_propDict["isDeleted"];
        } else {
            return null;
        }
    }
    
    /**
    * Sets the isDeleted
    *
    * @param bool $val The isDeleted
    *
    * @return OneDriveActivityUserDetail
    */
    public function setIsDeleted($val)
    {
        $this->_propDict["isDeleted"] = boolval($val);
        return $this;
    }
    
    /**
    * Gets the lastActivityDate
    *
    * @return \DateTime|null The lastActivityDate
    */
    public function getLastActivityDate()
    {
        if (array_key_exists("lastActivityDate", $this->_propDict)) {
            if (is_a($this->_propDict["lastActivityDate"], "\DateTime") || is_null($this->_propDict["lastActivityDate"])) {
                return $this->_propDict["lastActivityDate"];
            } else {
                $this->_propDict["lastActivityDate"] = new \DateTime($this->_propDict["lastActivityDate"]);
                return $this->_propDict["lastActivityDate"];
            }
        }
        return null;
    }
    
    /**
    * Sets the lastActivityDate
    *
    * @param \DateTime $val The lastActivityDate
    *
    * @return OneDriveActivityUserDetail
    */
    public function setLastActivityDate($val)
    {
        $this->_propDict["lastActivityDate"] = $val;
        return $this;
    }
    
    /**
    * Gets the reportPeriod
    *
    * @return string|null The reportPeriod
    */
    public function getReportPeriod()
    {
        if (array_key_exists("reportPeriod", $this->_propDict)) {
            return $this->_propDict["reportPeriod"];
        } else {
            return null;
        }
    }
    
    /**
    * Sets the reportPeriod
    *
    * @param string $val The reportPeriod
    *
    * @return OneDriveActivityUserDetail
    */
    public function setReportPeriod($val)
    {
        $this->_propDict["reportPeriod"] = $val;
        return $this;
    }
    
    /**
    * Gets the reportRefreshDate
    *
    * @return \DateTime|null The reportRefreshDate
    */
    public function getReportRefreshDate()
    {
        if (array_key_exists("reportRefreshDate", $this->_propDict)) {
            if (is_a($this->_propDict["reportRefreshDate"], "\DateTime") || is_null($this->_propDict["reportRefreshDate"])) {
                return $this->_propDict["reportRefreshDate"];
            } else {
                $this->_propDict["reportRefreshDate"] = new \DateTime($this->_propDict["reportRefreshDate"]);
                return $this->_propDict["reportRefreshDate"];
            }
        }
        return null;
    }
    
    /**
    * Sets the reportRefreshDate
    *
    * @param \DateTime $val The reportRefreshDate
    *
    * @return OneDriveActivityUserDetail
    */
    public function setReportRefreshDate($val)
    {
        $this->_propDict["reportRefreshDate"] = $val;
        return $this;
    }
    
    /**
    * Gets the sharedExternallyFileCount
    *
    * @return int|null The sharedExternallyFileCount
    */
    public function getSharedExternallyFileCount()
    {
        if (array_key_exists("sharedExternallyFileCount", $this->_propDict)) {
            return $this->_propDict["sharedExternallyFileCount"];
        } else {
            return null;
        }
    }
    
    /**
    * Sets the sharedExternallyFileCount
    *
    * @param int $val The sharedExternallyFileCount
    *
    * @return OneDriveActivityUserDetail
    */
    public function setSharedExternallyFileCount($val)
    {
        $this->_propDict["sharedExternallyFileCount"] = intval($val);
        return $this;
    }
    
    /**
    * Gets the sharedInternallyFileCount
    *
    * @return int|null The sharedInternallyFileCount
    */
    public function getSharedInternallyFileCount()
    {
        if (array_key_exists("sharedInternallyFileCount", $this->_propDict)) {
            return $this->_propDict["sharedInternallyFileCount"];
        } else {
            return null;
        }
    }
    
    /**
    * Sets the sharedInternallyFileCount
    *
    * @param int $val The sharedInternallyFileCount
    *
    * @return OneDriveActivityUserDetail
    */
    public function setSharedInternallyFileCount($val)
    {
        $this->_propDict["sharedInternallyFileCount"] = intval($val);
        return $this;
    }
    
    /**
    * Gets the syncedFileCount
    *
    * @return int|null The syncedFileCount
    */
    public function getSyncedFileCount()
    {
        if (array_key_exists("syncedFileCount", $this->_propDict)) {
            return $this->_propDict["syncedFileCount"];
        } else {
            return null;
        }
    }
    
    /**
    * Sets the syncedFileCount
    *
    * @param int $val The syncedFileCount
    *
    * @return OneDriveActivityUserDetail
    */
    public function setSyncedFileCount($val)
    {
        $this->_propDict["syncedFileCount"] = intval($val);
        return $this;
    }
    
    /**
    * Gets the userPrincipalName
    *
    * @return string|null The userPrincipalName
    */
    public function getUserPrincipalName()
    {
        if (array_key_exists("userPrincipalName", $this->_propDict)) {
            return $this->_propDict["userPrincipalName"];
        } else {
            return null;
        }
    }
    
    /**
    * Sets the userPrincipalName
    *
    * @param string $val The userPrincipalName
    *
    * @return OneDriveActivityUserDetail
    */
    public function setUserPrincipalName($val)
    {
        $this->_propDict["userPrincipalName"] = $val;
        return $this;
    }
    
    /**
    * Gets the viewedOrEditedFileCount
    *
    * @return int|null The viewedOrEditedFileCount
    */
    public function getViewedOrEditedFileCount()
    {
        if (array_key_exists("viewedOrEditedFileCount", $this->_propDict)) {
            return $this->_propDict["viewedOrEditedFileCount"];
        } else {
            return null;
        }
    }
    
    /**
    * Sets the viewedOrEditedFileCount
    *
    * @param int $val The viewedOrEditedFileCount
    *
    * @return OneDriveActivityUserDetail
    */
    public function setViewedOrEditedFileCount($val)
    {
        $this->_propDict["viewedOrEditedFileCount"] = intval($val);
        return $this;
    }
    
}
