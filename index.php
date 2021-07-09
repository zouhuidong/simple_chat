<?php
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
	
	$user = $_POST["user"];
	$submit = $_POST["submit"];
	$text = $_POST["text"];
	
	if($submit == "submit")
	{
		$result = mysqli_query($g_dbConnect, "INSERT INTO `message` (time,name,text,ip,location) VALUES ('".date("Y-m-d G:i:s")."','".$user."','".$text."','".GetClientIP()."','".GetIpAddress(GetClientIP())."');");
		if(!$result)
		{
			echo "<br/>ERROR: cannot insert message:".mysqli_error($g_dbConnect);
		}
	}
	
?>

<html>

	<head>
		<title>Simple Chat</title>
	</head>
	<body>
		
		<iframe
			id="message"
			src="./chat.php"
			width=100%
			height=50%
		>你的浏览器不支持iframe</iframe>
		
		<hr/>
		<button onclick="int=window.clearInterval(int)">停止接受消息</button>
		<button onclick="if(!int)int=set()">继续接收消息</button>
		<br/>
		<br/>
		
		<b>Simple Chat 聊天室</b>
		<p>无需注册，直接畅聊</p>
		
		<form method="post">
			临时昵称
			<input name="user" maxlength="30" size="30" value=<?php echo "'".$user."'"; ?>/>
			<br/><br/>
			<textarea name="text" rows="3" cols="70"></textarea>
			<button name="submit" value="submit">发送</button>
		</form>
		
		<!-- 定时器：不断刷新消息 -->
		<script>
			var int = set();
			
			function set()
			{
				return setInterval("document.getElementById('message').contentWindow.location.reload(true)",1000);
			}
		</script>
		
	</body>
</html>











