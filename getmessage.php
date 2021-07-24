<?php

	session_start();
	include_once("database.php");
	
	$res = mysqli_query($g_dbConnect,"select * from message");
	$message_num = mysqli_num_rows($res);

	// 如果不是第一次打开标签页，而且获取到了新消息
	if(isset($_SESSION['message_num']) && $message_num != $_SESSION['message_num'])
	{
		// 用户自己发了消息，而且新消息数为 1
		if($_SESSION['send'] && $message_num - $_SESSION['message_num'] == 1)
		{
			$_SESSION['send'] = false;
		}
		else
		{
			// 标志 chat 页面是获取到新消息后主动刷新的
			$_SESSION['call'] = true;
		}
		
		// 刷新聊天界面
		echo "
			<script>
				parent.document.getElementById('message_iframe').contentWindow.location.reload(true);
			</script>
		";
	}
	
	$_SESSION['message_num'] = $message_num;

?>