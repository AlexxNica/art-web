<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This Library is a CodeIgniter wrapper for the OpenId library provided by JanRain
 *
 * @package QwstOpenIdSessionAdapter
 * @author Carl Shelbourne <qwst-development@qwst.co.uk>
 * @copyright 2007 QWST Limited.
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */

/**
 * CodeIgniter OpenID consumer package
 *
 * This class object is the super class the every library in
 * CodeIgniter will be assigned to.
 *
 * @package		QwstOpenID
 * @subpackage	OpenId
 * @category	Libraries
 * @link		http://www.codeigniter.com/user_guide/general/controllers.html
 */

/**
 * This is the PHP OpenID library by JanRain, Inc.
 *
 * This module contains core utility functionality used by the
 * library.  See Consumer.php and Server.php for the consumer and
 * server implementations.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: See the COPYING file included in this distribution.
 *
 * @package OpenID
 * @author JanRain, Inc. <openid@janrain.com>
 * @copyright 2005 Janrain, Inc.
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */


/**
 * The base session class used by the Auth_Yadis_Manager.  This
 * class wraps the default PHP session machinery and should be
 * subclassed if your application doesn't use PHP sessioning.
 *
 * @package OpenID
 */

class QwstOpenIdSessionAdapter
{
    var $CI = null;
    var $oidConsumer = null;

    function QwstOpenIdSessionAdapter()
    {
        // This assumes that the DB and session classes are already available.
        // The assumption is also made that the Session class is either the session class
        // supplied with CI or that it is API compliant with the CI Session class, e.g.
        // OBsession.
        $this->CI =& get_instance();
        if ( $this->CI != null )
        {
        }
        else
        {
            log_message('error', 'CodeIgniter does not appear to be initialised in QwstOpenIdSessionAdapter');
        }
    }

    /**
     * Set a session key/value pair.
     *
     * @param string $name The name of the session key to add.
     * @param string $value The value to add to the session.
     */
    function set($name, $value)
    {
        $this->CI->session->set_userdata( $name, $value );
    }

    /**
     * Get a key's value from the session.
     *
     * @param string $name The name of the key to retrieve.
     * @param string $default The optional value to return if the key
     * is not found in the session.
     * @return string $result The key's value in the session or
     * $default if it isn't found.
     */
    function get($name, $default=null)
    {
        $result = $this->CI->session->userdata( $name );
        if ( empty( $result ) )
        {
            $result = $default;
        }
        return $result;
    }

    /**
     * Remove a key/value pair from the session.
     *
     * @param string $name The name of the key to remove.
     */
    function del($name)
    {
        $this->CI->session->unset_userdata( $name );
    }

    /**
     * Return the contents of the session in array form.
     */
    function contents()
    {
        return $this->CI->session->all_userdata();
    }
}
?>