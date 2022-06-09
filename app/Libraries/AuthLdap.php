<?php namespace App\Libraries;

/**
 * Class AuthLdap
 * @package AuthLdap\Libraries
 * @author Karthikeyan C <karthikn.mca@gmail.com>
 */
class AuthLdap
{
    /**
     * LDAP Configuration
     * @var \AuthLdap\Config\AuthLdap $config
     */
    private $config;

    /**
     * LDAP Connection Resource
     * @var resource $ldapResource
     */
    private $ldapResource;

    /**
     * AuthLdap constructor.
     */
    public function __construct()
    {
        // LDAP Configuration
        $this->config = new \Config\AuthLdap();
    }

    /**
     * @param $userName
     * @param $password
     * @return array
     * @author Karthikeyan C <karthikn.mca@gmail.com>
     */
    private function _authenticate($userName, $password): array
    {
        $ldapBind = ldap_bind($this->ldapResource);
        if (!$ldapBind)
        {
            log_message('error', 'Unable to bind LDAP');
        }
        $filterCriteria     =   "({$this->config->getLdapUserAttribute()}={$userName})";
		$searchAttributes	=	$this->config->getLdapSearchAttribute();
		$searchAttributes	=	array_merge($searchAttributes, [$this->config->getLdapUserAttribute()]);
        $ldapSearchResource =   ldap_search(
                                    $this->ldapResource,
                                    $this->config->getLdapBaseDN(),
                                    $filterCriteria,
									$searchAttributes
                                );
		if (!is_resource($ldapSearchResource)
				|| get_resource_type($ldapSearchResource) != 'ldap result')
		{
			log_message('error', 'LDAP Search failure! Either connectivity issue or server not responding');
		}
        
        $ldapEntries        =   ldap_get_entries($this->ldapResource, $ldapSearchResource);
        // var_dump($filterCriteria);
        // var_dump($this->ldapResource);
        // var_dump($ldapSearchResource);
        // var_dump($this->config->getLdapBaseDN());
        // var_dump($password);
        // var_dump($searchAttributes);
        // var_dump($ldapEntries);die;
        if ($ldapEntries['count'] == 0) {
            log_message("error","Verify your LDAP configuration");
            return [];
        }
        $ldapBindRdn        =   $ldapEntries[0]['dn'];
        $isLdapBinded       =   @ldap_bind($this->ldapResource, $ldapBindRdn, $password);
        if (!$isLdapBinded)
        {
            log_message("warning","Login attempted by {$userName} on IP {$_SERVER['REMOTE_ADDR']}");
            return [];
        }
        $cn =   $ldapEntries[0]['cn'][0];
        $dn =   stripslashes($ldapEntries[0]['dn']);
        $this->setUserAttributesFromLdap();
		$memberOfGroups	=	$this->config->getRoleByUserName($userName);
        return [
            'cn' => $cn,
            'dn' => $dn,
            'id' => $userName,
            'role' => $memberOfGroups[0],
            $this->config->getLdapMemberOfGroupsIdentifier() => $memberOfGroups
        ];
    }

    /**
     * Search and set all Group Entries along with UserIDs
     * @author Karthikeyan C <karthikn.mca@gmail.com>
     */
    private function setUserAttributesFromLdap(): void
    {
        $filterCriteria     =   "({$this->config->getLdapGroupAttribute()}=*)";
		$searchAttributes	=	$this->config->getLdapSearchAttribute();
		$searchAttributes	=	array_merge($searchAttributes, [$this->config->getLdapGroupAttribute(), 'uniqueMember']);
		$ldapSearchResource =   ldap_search(
									$this->ldapResource,
									$this->config->getLdapBaseDN(),
									$filterCriteria,
									$searchAttributes
								);
		if (!is_resource($ldapSearchResource)
				|| get_resource_type($ldapSearchResource) != 'ldap result')
        {
            log_message('error', 'LDAP Search failure! Either connectivity issue or server not responding');
        }
        $ldapEntries = ldap_get_entries($this->ldapResource, $ldapSearchResource) ?? [];
        if (!empty($ldapEntries))
        {
            foreach ($ldapEntries as $iteration => $ldapEntry)
            {
                if (!isset($ldapEntry['ou'][0])) {continue;}
                $groupName = $ldapEntry['ou'][0];
                if (is_array($ldapEntry))
                {
                    unset($ldapEntry[$this->config->getLdapMemberOfGroupsIdentifier()]['count']);
                    $userNameArray  =   array_map(
                        function($dnString) {
                            preg_match('/^uid=([a-zA-Z0-9]{0,})/i', $dnString, $uidString);
                            return $uidString[1];
                        },
                        $ldapEntry[$this->config->getLdapMemberOfGroupsIdentifier()]
                    );
                }
                if (isset($groupName, $userNameArray))
                {
                    $this->config->setGroup($groupName, $userNameArray);
                    foreach ($userNameArray as $userName)
                    {
                        $this->config->setUserAndGroup($userName, $groupName);
                    }
                }
            }
        }
    }

    /**
     * Search and get all Group Entries as follows
     *   Array
     *   (
     *       [count] => 2
     *       [0] => Array
     *       (
     *           [ou] => Array
     *           (
     *               [count] => 1
     *               [0] => mathematicians
     *           )
     *           [0] => ou
     *           [cn] => Array
     *           (
     *               [count] => 1
     *               [0] => Mathematicians
     *           )
     *           [1] => cn
     *           [count] => 2
     *           [dn] => ou=mathematicians,dc=example,dc=com
     *       )
     *       [1] => Array
     *       (
     *           [ou] => Array
     *           (
     *               [count] => 1
     *               [0] => scientists
     *           )
     *           [0] => ou
     *           [cn] => Array
     *           (
     *               [count] => 1
     *               [0] => Scientists
     *           )
     *           [1] => cn
     *           [count] => 2
     *           [dn] => ou=scientists,dc=example,dc=com
     *       )
     *   )
     * @return array
     * @author Karthikeyan C <karthikn.mca@gmail.com>
     */
    public function getAllGroups(): array
    {
            $filterCriteria     =   "({$this->config->getLdapGroupAttribute()}=*)";
			$searchAttributes	=	$this->config->getLdapSearchAttribute();
			$searchAttributes	=	array_merge(
										$searchAttributes,
										[
											$this->config->getLdapMemberOfGroupsIdentifier(),
											$this->config->getLdapGroupAttribute()
										]
									);
            $ldapSearchResource =   ldap_search(
                                        $this->ldapResource,
                                        $this->config->getLdapBaseDN(),
                                        $filterCriteria,
										$searchAttributes
                                    );
            if (!is_resource($ldapSearchResource)
					|| get_resource_type($ldapSearchResource) != 'ldap result')
            {
                log_message('error', 'LDAP Search failure! Either connectivity issue or server not responding');
                return [];
            }
            return ldap_get_entries($this->ldapResource, $ldapSearchResource) ?? [];
    }

    /**
     * Get Individual Entries from LDAP
     *   Array
     *   (
     *       [count] => 2
     *       [0] => Array
     *       (
     *           [uid] => Array
     *           (
     *               [count] => 1
     *               [0] => newton
     *           )
     *           [0] => uid
     *           [cn] => Array
     *           (
     *               [count] => 1
     *               [0] => Isaac Newton
     *           )
     *           [1] => cn
     *           [count] => 2
     *           [dn] => uid=newton,dc=example,dc=com
     *       )
     *       [1] => Array
     *       (
     *           [cn] => Array
     *           (
     *               [count] => 1
     *               [0] => Albert Einstein
     *           )
     *           [0] => cn
     *           [uid] => Array
     *           (
     *               [count] => 1
     *               [0] => einstein
     *           )
     *           [1] => uid
     *           [count] => 2
     *           [dn] => uid=einstein,dc=example,dc=com
     *       )
     *   )
     * @return array
     * @author Karthikeyan C <karthikn.mca@gmail.com>
     */
    public function getAllUsers(): array
    {
        $filterCriteria     =   "({$this->config->getLdapUserAttribute()}=*)";
		$searchAttributes	=	$this->config->getLdapSearchAttribute();
		$searchAttributes	=	array_merge(
									$searchAttributes,
									['sn', 'ou', $this->config->getLdapUserAttribute()]
								);
        $ldapSearchResource =   ldap_search(
                                    $this->ldapResource,
                                    $this->config->getLdapBaseDN(),
                                    $filterCriteria,
									$searchAttributes
                                );
        if (!is_resource($ldapSearchResource)
				|| get_resource_type($ldapSearchResource) != 'ldap result')
        {
            log_message('error', 'LDAP Search failure! Either connectivity issue or server not responding');
            return [];
        }
        return ldap_get_entries($this->ldapResource, $ldapSearchResource) ?? [];
    }

    /**
     * @param $userName
     * @param $password
     * @return array
     * @author Karthikeyan C <karthikn.mca@gmail.com>
     */
    function authenticate($userName, $password): array
    {
        $ldapAuthenticatedUser = $this->_authenticate($userName,$password);
        if (empty($ldapAuthenticatedUser))
        {
            log_message('info', "{$userName} is not found in the Server.");
            return [];
        }
        return [
            'fullname'  	=>  $ldapAuthenticatedUser['cn'],
            'username'  	=>  $userName,
            'role'      	=>  $ldapAuthenticatedUser['role'],
			'roles_mapped'	=>	$ldapAuthenticatedUser[$this->config->getLdapMemberOfGroupsIdentifier()]
        ];
    }

    public function setConfig($ldapConfig) {
        if (isset($ldapConfig['baseDn'])) { $this->config->setBaseDn($ldapConfig['baseDn']); }
        if (isset($ldapConfig['ldapDomain'])) { $this->config->setLdapDomain($ldapConfig['ldapDomain']); }
        if (isset($ldapConfig['useTls'])) { $this->config->setUseTls($ldapConfig['useTls']); }
        if (isset($ldapConfig['tcpPort'])) { $this->config->setTcpPort($ldapConfig['tcpPort']); }

        //Establishing Connection
		$this->ldapResource = ldap_connect($this->config->getLdapUrl());
		if (!is_resource($this->ldapResource)
				|| get_resource_type($this->ldapResource) != 'ldap link')
		{
			log_message('info', "Unable to connect LDAP on {$this->config->getLdapUrl()}");
		}

		if ($this->config->isTlsEnabled())
		{
			log_message('info', 'Attempting to use TLS on LDAP');
			ldap_start_tls($this->ldapResource);
		}
		ldap_set_option($this->ldapResource, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($this->ldapResource, LDAP_OPT_REFERRALS, 0);
    }
}
