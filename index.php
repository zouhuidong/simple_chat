<?php
	include_once("database.php");
	
	// �õ��ͻ���IP��ַ
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
	
	// �õ�ĳIP�Ĺ����أ����ع������ַ���
	function GetIpAddress($ip)
	{
		//�õ�IP������
		$location = file_get_contents("https://whois.pconline.com.cn/jsLabel.jsp?ip=".$ip);
		
		//ת���ַ�����UTF-8 -> GBK
		//$location = mb_convert_encoding($location, 'utf-8','GB2312');
		
		//ɾȥ������Ϣ��ֻ������������Ϣ
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
		>����������֧��iframe</iframe>
		
		<hr/>
		<button onclick="int=window.clearInterval(int)">ֹͣ������Ϣ</button>
		<button onclick="if(!int)int=set()">����������Ϣ</button>
		<br/>
		<br/>
		
		<b>Simple Chat ������</b>
		<p>����ע�ᣬֱ�ӳ���</p>
		
		<form method="post">
			��ʱ�ǳ�
			<input name="user" maxlength="30" size="30" value=<?php echo "'".$user."'"; ?>/>
			<br/><br/>
			<textarea name="text" rows="3" cols="70"></textarea>
			<button name="submit" value="submit">����</button>
		</form>
		
		<!-- ��ʱ��������ˢ����Ϣ -->
		<script>
			var int = set();
			
			function set()
			{
				return setInterval("document.getElementById('message').contentWindow.location.reload(true)",1000);
			}
		</script>
		
	</body>
</html>










