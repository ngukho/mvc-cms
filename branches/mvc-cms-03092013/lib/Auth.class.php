<?php
/**
* Auth class. Used for all login/logout stuff.
*/
class Auth
{
    var $table, $userNameField, $passField, $miscField,$lastLoggedInField;
    var $loggedIn;
    var $homePageUrl, $loginPageUrl, $membersAreaUrl;
    var $obj;

    function Auth()
    {
        $this->table='your_table';
        //The fields below should be columns in the table above, which are used to
        //authenticate the user's credentials.
        $this->userNameField='username';
        $this->passField='password';

        //The numeric column which stores the permissions/level of each user:
        $this->lvlField='lvl'; 

        //The following are general columns in the database which are
        //stored in the Session, for easily displaying some information
        //about the user:
        $this->miscFields='id,first,email,lvl,verified,credits'; 

        /* If there is a no lastLoggedIn field in the table which is updated
               to the current DATETIME whenever the user logs in, set the next
              variable to blank to disable this feature. */

        $this->lastLoggedInField='last_login';

        $this->homePageUrl=site_url();
        $this->loginPageUrl=site_url('accounts/login');
        $this->membersAreaUrl=site_url();

        //This is a CodeIgniter specific variable used to refer to the base
        //CodeIgniter Object: 
        $this->obj=&get_instance();

        //This is my custom database library:
        $this->db=$this->obj->db;

        //All data passed on from a form to this class must be 
        // already escaped to prevent SQL injection.
         //However, all data stored in sessions is escaped by the class.

        if ($this->isLoggedIn())
                $this->refreshInfo();

    }
    function checkLogin($user, $pass)
    {
        $sql="SELECT $this->miscFields FROM $this->table 
        WHERE $this->userNameField='$user' AND $this->passField='$pass'";
        $query=$this->db->query($sql);
        return ($query->num_rows() ===1);
    }

    function isSessLoggedIn()
    {
        if ($this->loggedIn==='yes')
                return true;
        $user=$this->escapeStr($this->obj->session->userdata('user'));
        $pass=$this->escapeStr($this->obj->session->userdata('pass'));

        if ($this->checkLogin($this->escapeStr($user),$this->escapeStr($pass),0))
        {
                $this->loggedIn='yes';
                return true;
        } 
        else
        {
                $this->loggedIn=FALSE;
                return false;
        }
    }

    function isCookieLoggedIn()
    {
        if (! array_key_exists('user',$_COOKIE) || ! array_key_exists('pass',$_COOKIE))
                return false;
        $user=$this->escapeStr($_COOKIE['user']);
        $pass=$this->escapeStr($_COOKIE['pass']);
        if ($this->checkLogin($user,$pass))
                $loggedIn=TRUE;
        else
                $loggedIn=FALSE;
        if ($loggedIn && ! $this->isSessLoggedIn())
        {
                $sql="SELECT $this->passField FROM $this->table 
                WHERE $this->userNameField='$user' LIMIT 1";
                $query=$this->db->query($sql);
                $pass=$query->getSingle($this->passField);
                $this->login($user,$pass);
        }
        return $loggedIn;
    }

    function isLoggedIn()
    {
        return ($this->isSessLoggedIn() || $this->isCookieLoggedIn());
    }



    function login($user, $pass,$remember=FALSE)
    {

        if ($this->isSessLoggedIn())
                return false;

        if (! $this->checkLogin($user,$pass))
                return false;

        $this->obj->session->set_userdata('user',$user);        
        $this->obj->session->set_userdata('pass',$pass);

        $sql="SELECT $this->miscFields FROM $this->table 
        WHERE $this->userNameField='$user' && $this->passField='$pass'";
        $query=$this->db->query($sql);
        $fields=explode(',',$this->miscFields);
        foreach ($fields as $k=>$v)
        {
                $fieldName=$v;
                $fieldVal=$query->getSingle($v);
                $this->obj->session->set_userdata($fieldName,$fieldVal);
        }


        if ($this->lastLoggedInField !='')
        {
                $sql="UPDATE $this->table SET 
                $this->lastLoggedInField=NOW(),num_logins=num_logins + 1 
                WHERE $this->userNameField='$user' && $this->passField='$pass'";
                $this->db->query($sql);
        }

        if ($remember)
                $this->setCookies();
        return true;
    }


    function logout($redir=true)
    {
        if (! $this->isLoggedIn())
                return false;

        $this->obj->session->sess_destroy();

        if ($this->isCookieLoggedIn())
        {
                setcookie('user','', time()-36000, '/');
                setcookie('pass','', time()-36000, '/');
        }
        if (! $redir)
                return;

        header('location: '.$this->homePageUrl);
        die;
    }


    function restrict($minLevel)
    {
        if (! is_numeric($minLevel) && $minLevel!='ADMIN')
                return false;


        //URL of the page the user was trying to access, so upon logging in
        // he is redirected back to this url.
        $url=$this->obj->uri->uri_string();
        if (! $this->isLoggedIn())
        {
                $this->obj->session->set_userdata('redirect_url',$url);
                header('location: '.$this->loginPageUrl);
                die;
        }

        if ($this->obj->session->userdata($this->lvlField) < $minLevel)
        {
                header('location: '.$this->membersAreaUrl);
                die;
        }
        return true;
    }


    function setCookies()
    {
        if (! $this->isSessLoggedIn())
        {
                return false;
        }
        $user=$this->obj->session->userdata('user');
        $pass=$this->obj->session->userdata('pass');

        @setcookie('user',$user, time()+60*60*24*30, '/');
        @setcookie('pass',$pass, time()+60*60*24*30, '/');
        return true;
    }


    //This function refreshes all the info in the Session, so if a user changed
    //his name, for example, his name in the Session is updated
    function refreshInfo()
    {
        if (! $this->isLoggedIn())
                return false;
        $id=trim($this->obj->session->userdata('id'));
        $sql="SELECT $this->passField,$this->userNameField, 
        $this->miscFields FROM $this->table WHERE id='$id' LIMIT 1";
        $query=$this->db->query($sql);
        $info['pass']=$query->getSingle($this->passField);
        $info['user']=$query->getSingle($this->userNameField);
        $fields=explode(',',$this->miscFields);
        foreach ($fields as $k=>$v)
        {
                $info[$v]=$query->getSingle($v);
        }

        //The following variables are used to determine wether or not to
        //set the cookies on the users computer. If $origUser matches the
        //cookie value 'user' it means the user had cookies stored on his 
        //browser, so the cookies would be re-written with the new value of the
        //username.
        $origUser=$this->obj->session->userdata('user');
        $origPass=$this->obj->session->userdata('pass');
        foreach ($info as $k=>$v)
        {
                $this->obj->session->set_userdata($k,$v);
        }

        if (array_key_exists('user',$_COOKIE) && array_key_exists('pass',$_COOKIE))
        {
                if ($_COOKIE['user']==$origUser && $_COOKIE['pass']==$origPass)
                        $this->setCookies();
        }
        return true;
    }


    function isAdmin()
    {
        if (! $this->isLoggedIn())
                return false;
        $lvl=$this->obj->session->userdata('lvl');

        return ($lvl >= 2);
    }           


    function isVerified()
    {
        return ($this->obj->session->userdata('verified')=='1');
    }

	private function escapeStr($str)
	{
		return trim(mysql_real_escape_string($str));
	}	
}


/**
 * Used for quickly doing mysql_real_escape() and trim() on a string.
 */ 
function escapeStr($str)
{
    return trim(mysql_real_escape_string($str));
}
?>