<?php

	/*
	 *	index.php
	 *	simple_chat 聊天系统主页面
	 *
	 *	制作者：huidong<mailkey@yeah.net>
	 *	创建时间：2021.7.19
	 *	最后修改：2021.7.23
	 *
	 */

	session_start();

	// simple_chat 版本信息
	$g_version = "Ver 0.2(beta)";
	$g_release_time = "2021.7.23";
	
	// 规定是否允许输入 html 标签（script标签除外）
	$_SESSION['isAllowHTML'] = false;
	
	// 规定是否允许文件上传
	$g_allow_file_upload = true;
	
	// 文件上传配置参数
	$_SESSION['control_upload_file_exts'] = array("*");
	$_SESSION['control_upload_file_max_size'] = 512;
	$_SESSION['control_upload_file_size_unit'] = "MB";
	$_SESSION['control_upload_file_save_path'] = "./upload/";
	$_SESSION['control_upload_file_allow_repeat'] = true;
	
?>

<html>

	<head>
		<meta charset="utf-8">
		<title>Simple Chat</title>
		
		<style>
		
			/* 手机 */
			[class*="message"] {
				width: 100%;
			}
			
			/* 电脑 */
			@media only screen and (min-width: 1300px) {
				.message {
					float:right;
					width: 50%;
				}
			}
			
			.box {
				height:70%;
				overflow: hidden;
			}
			
		</style>
		
	</head>
	<body style="
		padding-left: 30px;
		padding-right: 30px;
	">
		
		<!-- 左半边 -->
		<div style="float:left;">

			<h1 style='font-family: "Times New Roman", Times, serif;font-size:40px'>Simple Chat 聊天室</h1>
			<font size=2>
				<font color=blue>
					<?php echo $g_version ?>
				</font>
				<font color=grey>
				made by huidong&lt;mailkey@yeah.net&gt; <?php echo $g_release_time ?>
				</font>
			</font>
			<p>无需注册，直接畅聊</p>
			<br/>
			
			<form method="post" target="sendmessage" id="form" action="./sendmessage.php">
				<b>
					临时昵称&nbsp;
					<input id="user" name="user" maxlength="30" size="30" required></input>
				</b><br/><br/>
				<textarea id="text" name="text" rows="6" cols="70"></textarea>
				<br/><br/>
				<div style="float:right">
					<button name="button" value="submit" style="
						width:100px;
						height:50px;
						background-color:blue;
						color:white;
						border:none;
					">发送</button>
				</div>
			</form>
			
			<!-- 文件上传 -->
			<?php
				if($g_allow_file_upload)
				{
					echo "
						<b> 文件上传【提交文件后需要点击发送】 </b><br/>
						<iframe width=400px height=100px src='./control_upload_file.php'></iframe>
					";
				}
			?>
			
			
			<!-- 无刷提交表单 -->
			<iframe name="sendmessage" style="display:none;" ></iframe>
			
		</div>
		<br/>
		
		<!-- 右半边：聊天消息窗口 -->
		<div id="messagebox" class="message box">
			<iframe
				id="message_iframe"
				src="./chat.php"
				width=100%
				height=100%
			>你的浏览器不支持iframe</iframe>
		</div>
		
		<div style="clear:both"></div>
		<hr/>
		
		<!-- 后台持续获取信息 -->
		<iframe
			id="getmessage"
			src="./getmessage.php"
			style="display:none;"
		></iframe>
		
		<!-- js 代码 -->
		<script>
		
			// 设置定时器，不断刷新消息
			var int = set();
			function set()
			{
				return setInterval("document.getElementById('getmessage').contentWindow.location.reload(true)",1000);
			}
			
		</script>
		
	</body>
</html>











