<?php

	session_start();
	include_once("database.php");
	
	// 得到客户端IP地址
	function GetClientIP()
	{
		$ip = null;
		
		if ($_SERVER["HTTP_CLIENT_IP"] && strcasecmp($_SERVER["HTTP_CLIENT_IP"], "unknown"))
		{
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		else
		{
			if ($_SERVER["HTTP_X_FORWARDED_FOR"] && strcasecmp($_SERVER["HTTP_X_FORWARDED_FOR"], "unknown"))
			{
				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			}
			else
			{
				if ($_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown"))
				{
					$ip = $_SERVER["REMOTE_ADDR"];
				}
				else
				{
					if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
					{
						$ip = $_SERVER['REMOTE_ADDR'];
					}
					else
					{
						$ip = "unknown";
					}
				}
			}
		}
		
		return $ip;
	}
	
	// 得到某IP的归属地，返回归属地字符串
	function GetIpAddress($ip)
	{
		//得到IP归属地
		$location = file_get_contents("https://whois.pconline.com.cn/jsLabel.jsp?ip=".$ip);
		
		//转换字符集，UTF-8 -> GBK
		//$location = mb_convert_encoding($location, 'utf-8','GB2312');
		
		//删去无用信息，只保留归属地信息
		$location = substr($location,strrpos($location,"='")+2);
		$location = substr($location,0,strpos($location,"'"));
		
		return $location;	
	}
	
	// 清除HTML字符串内的html,js,css格式，返回清除格式后的HTML字符串内容
	// $str 原字符串
	function DeleteHtml($str)
	{
		$str = trim($str); //清除字符串两边的空格
		$str = strip_tags($str,""); //利用php自带的函数清除html格式
		$str = preg_replace("/\t/","",$str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
		$str = preg_replace("/\r\n/","",$str);
		$str = preg_replace("/\r/","",$str);
		$str = preg_replace("/\n/","",$str);
		$str = preg_replace("/ /","",$str);
		$str = preg_replace("/ /","",$str); //匹配html中的空格
		return trim($str); //返回字符串
	}
	

	// 表单消息处理
	
	//$submit = $_POST["button"];
	$user = $_POST["user"];
	$text = $_POST["text"];
	$ip = GetClientIP();
	
	if(!empty($user))
	{
		if($_SESSION['control_upload_file_state'] == 0 && empty($text))
		{
			return;
		}
		
		// 安全性处理
		if(!$_SESSION['isAllowHTML'])
		{
			$user = htmlspecialchars($user);
			$text = htmlspecialchars($text);
		}
		else
		{
			$user = str_replace("<script","",$user);
			$user = str_replace("</script","",$user);
			$text = str_replace("<script","",$text);
			$text = str_replace("</script","",$text);
		}
		
		// 上传了文件
		if($_SESSION['control_upload_file_state'] != 0)
		{	
			switch($_SESSION['control_upload_file_state'])
			{
			case 1:
				$text .= "
					<br/>
					<div style='border:1px solid black;width:90%;word-break:break-all;padding:0px 10px 10px 10px;'>
						<b>附件：</b>
						大小：".($_SESSION['control_upload_file_size'] / 1048576)." MB
						<b>下载链接：</b>
						<a href='{$_SESSION['control_upload_file_save_path']}{$_SESSION['control_upload_file_name']}' download='' target='blank'>{$_SESSION['control_upload_file_name']}</a>
					</div>
				";
				
				break;
			case 2:
				echo "<script>alert('文件后缀名不符合要求。');</script>";
				break;
			case 3:
				echo "<script>alert('文件太大了。');</script>";
				break;
			case 4:
				echo "<script>alert('此文件已存在~ 请勿重复上传。');</script>";
				break;
			default:
				echo "<script>alert('文件上传时遇到错误，详细信息：\\n{$_SESSION['control_upload_file_state']}');</script>";
				break;
			}
			
			// 状态归零
			$_SESSION['control_upload_file_state'] = 0;
		}
		
		// 特殊字符转义
		$user = addslashes($user);
		$text = addslashes($text);
		
		// 换行转为 html 的换行
		$text = str_replace("\r","",$text);
		$text = str_replace("\n","<br/>",$text);
		
		echo $text;
		
		// 插入数据库
		$result = mysqli_query($g_dbConnect, "
			INSERT INTO `message` (time,name,text,ip,location) VALUES (
			'".date("Y-m-d G:i:s")."',
			'".$user."',
			'".$text."',
			'".$ip."',
			'".iconv('gbk','utf-8',GetIpAddress($ip))."'
			);
		");
		
		if(!$result)
		{
			echo "<script>alert('ERROR: cannot insert message:".mysqli_error($g_dbConnect)."');";
		}
		
		// 标志是当前用户主动发的消息
		$_SESSION['send'] = true;
		
		// 发送消息后，清空表单的消息内容
		echo "
			<script>
				parent.document.getElementById('text').value='';
			</script>
		";
	}

?>