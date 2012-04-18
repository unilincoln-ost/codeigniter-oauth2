<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Lincoln OAuth2 Provider
 *
 * @package    CodeIgniter/OAuth2
 * @category   Provider
 * @author     Phil Sturgeon
 * @copyright  (c) 2012 HappyNinjas Ltd
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

class OAuth2_Provider_Lincoln extends OAuth2_Provider
{
    /**
     * @var  string  default scope (useful if a scope is required for user info)
     */
    protected $scope = array('user.basic,user.courses,user.contact,user.print.balance');//Defaults basic info not empty
	
    /**
     * @var  string  the method to use when requesting tokens
     */
    protected $method = 'POST';

	public function url_authorize()
	{
		return 'https://sso.lincoln.ac.uk/oauth';
	}

	public function url_access_token()
	{
		return 'https://sso.lincoln.ac.uk/oauth/access_token';
	}	

    public function get_user_info(OAuth2_Token_Access $token)
    {
        $url = 'https://nucleus.lincoln.ac.uk/v1/people/user?' . http_build_query(array(
            'access_token' => $token->access_token
        ));

        $user = json_decode(file_get_contents($url));
		$user = $user->results[0];
		return $user;
    }

}
 