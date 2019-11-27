<?php

class ADVerify extends adLDAP {

  /**
  * Find information about users by email address
  *
  * @param string $email The username to query
  * @param array $fields Array of parameters to query
  * @param bool $isGUID Is the username passed a GUID or a samAccountName
  * @return array
  */
  public function user_info_by_email($email,$fields=NULL,$isGUID=false){
    if ($email===NULL){ return (false); }
    if (!$this->_bind){ return (false); }

    $filter="mail=".$email;
    $filter = "(&(objectCategory=person)({$filter}))";
    if ($fields===NULL){ $fields=array("samaccountname","mail","memberof","department","displayname","telephonenumber","primarygroupid","objectsid"); }
    if (!in_array("objectsid",$fields)){
      $fields[] = "objectsid";
    }
    $sr=ldap_search($this->_conn,$this->_base_dn,$filter,$fields);
    $entries = ldap_get_entries($this->_conn, $sr);

    if (isset($entries[0])) {
      if ($entries[0]['count'] >= 1) {
        if (in_array("memberof", $fields)) {
          // AD does not return the primary group in the ldap query, we may need to fudge it
          if ($this->_real_primarygroup && isset($entries[0]["primarygroupid"][0]) && isset($entries[0]["objectsid"][0])){
            //$entries[0]["memberof"][]=$this->group_cn($entries[0]["primarygroupid"][0]);
            $entries[0]["memberof"][]=$this->get_primary_group($entries[0]["primarygroupid"][0], $entries[0]["objectsid"][0]);
          } else {
            $entries[0]["memberof"][]="CN=Domain Users,CN=Users,".$this->_base_dn;
          }
          $entries[0]["memberof"]["count"]++;
        }
      }
      return $entries;
    }
    return false;
  }

  /**
  * Verify if at least one user is found in the AD with a given email address
  *
  * @param string $email The username to query
  * @return boolean
  */
  public function is_email_found($email){
    if ($email===NULL){ return (false); }
    if (!$this->_bind){ return (false); }

    $result = $this->user_info_by_email($email);
    $isFound = ( $result && isset( $result['count'] ) && $result['count'] > 0 )? true: false;

    return $isFound;
  }
}

?>
