<?php
class Security
{
	private $type;
	private $data;
	private $params;
	private $exceptions;
	private $clean;
	public function Secure($type, $data, $parameters, $exceptions)
	{
		if (!isset($type))
		{
			die("No type defined");
		}
		else
		{
			switch($type)
			{
				case "xss" :
					$this->type = 'xss';
					$this->data = $data;
					$this->exceptions = $exceptions;
					if (!empty($parameters))
					{
						$this->params = $parameters;
					}
					$clean = $this->SortNow();
					return $this->clean;
					break;
				case 'csrfcheck':
					$this->type = 'csrfcheck';
					$this->data = $data;
					$clean = $this->SortNow();
					if ($clean === true)
					{
						return true;
					}
					else
					{
						die;
					}
					break;
				case 'csrfgenerate':
					$this->type = 'csrfgenerate';
					$this->data = $data;
					$clean = $this->SortNow();
					break;
				case 'csrfform':
					$this->type = 'csrfform';
					$this->data = $data;
					$clean = $this->SortNow();
					return $this->clean;
					break;
				default:
				echo "No type specified";
			}
		}
	}
	
	private function SortNow()
	{
		switch ($this->type)
		{
			case 'xss':
				$clean = $this->AntiXSS();
				return true;
				break;
			case 'csrfcheck':
				$clean = $this->CSRFCheck();
				return $clean;
				break;
			case 'csrfgenerate':
				$this->CSRFGenerateToken();
				return true;
				break;
			case 'csrfform':
				$this->CSRFFormInput();
				return true;
				break;
			case 'email':
				$this->Email();
				break;
			case 'rfi':
				$this->RFI();
				break;
			case 'lfi':
				$this->LFI();
				break;
				
			default:
			echo "No type specified";
		}
	}
	
	private function CSRFGenerateToken()
	{
		if (!isset($_SESSION['token']))
		{
			$r = unpack('v*', fread(fopen('/dev/urandom', 'rb'),50));
			$_SESSION['token'] = implode($r);
			$temp = $_SESSION['token'];
			return true;
		}
		else
		{
			return true;
		}
	}
	
	private function CSRFFormInput()
	{
		$this->clean =  '<input type="hidden" name="token" value="'.$_SESSION[token].'">';
		return true;
	}
	
	private function CSRFCheck()
	{
		if (!isset($_SESSION['token']))
		{
			$this->CSRFGenerateToken();
		}
		else
		{
			if ($_SESSION['token'] == $this->data)
			{
				return true;
			}
			else
			{
				error_log("CSRF attack: ".$_SERVER['REMOTE_ADDR'], $this->data, 0);
				return false;
			}
		}
	}
	
	private function Email()
	{
		return $this->data;
	}
		
	private function RFI()
	{
		return $this->data;
	}
	
	private function LFI()
	{
		return $this->data;
	}
	
	private function AntiXSS()
	{
		if (!isset($this->params))
		{
			echo "XSS filtering requires at least one parameter";
			die;
		}
		else
		{
			error_log("this->param: ".$this->params, 0);
		}
		
		switch ($this->params)
		{
			case "string":
				$clean = $this->AntiXSS1();
				return true;
				break;
			case "num":
				$clean = $this->AntiXSS2();
				return true;
				break;
			case "white":
				$clean = $this->AntiXSS3();
				return true;
				break;
			case "email":
				$clean = $this->AntiXSS4();
				return true;
				break;
			case "admin":
				$clean = $this->AntiXSS5();
				return true;
				break;
			default:
				echo $string;
			break;
		}
	}
	
	private function AntiXSS1()
	{
		$clean = preg_replace("/[^A-Za-z0-9.'-:\s]/", "", $this->data);
		$this->clean = $clean;
		return true;
	}
	
	private function AntiXSS2()
	{
		$clean = preg_replace('/[^0-9.]*/', "", $this->data);
		$this->clean = $clean;
		return true;
	}
	
	private function AntiXSS3()
	{
		$temp;
		for ($i = 0; $i<strlen($this->exceptions); $i++)
		{
			$character = substr($this->exceptions, $i,1);
			$temp .=".".$character;
		}
		$clean = preg_replace("/[^A-Za-z".$temp."\s]/", "", $this->data);
		$this->clean = $clean;
		return true;
	}
	
	private function AntiXSS4()
	{
		$clean = preg_replace("/[^A-Za-z0-9.@\s]/", "", $this->data);
		$this->clean = $clean;
		return true;
	}
	
	private function AntiXSS5()
	{
		$clean = preg_replace("/[^A-Za-z0-9@<?\/\>]/", "", $this->data);
		$clean2 = str_replace(array("<script>", "</script>"), "", $clean);
		$this->clean = $clean2;
		return true;
	}
}
?>
