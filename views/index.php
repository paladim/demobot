<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $name ?></title>

    <link href="css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <!-- div widget -->
  <div class="widget">

    <div class="chats-box">
      <div class="chats-box__header">
        <img class="chats-box__header__img" src="img/php_elephant.svg" alt="logo" />
        <span class="chats-box__header__title"> <b>PHP</b> Developers contest </span> 
      </div>
      <div class="chats-list">
      </div>
    </div>

    <!-- div messages box -->
    <div class="messages-box">
      <div class="message-box__header">
        <div class="star">
          <i id="star" class="fa fa-star star-img" aria-hidden="true"></i>
        </div>
        <div class="out-message-box">
          <input class="out-message" type="text" name="out-message" placeholder="Сообщение...">
          <!-- <img class="sendoutmessage" src="img/send.png" alt="logo" /> -->
          <i class="send-out-message fa fa-paper-plane" style="color: white;" aria-hidden="true"></i>
        </div>
      </div>
      <div class="messages">
      </div>
    </div>
    <!-- div messages box -->

  </div>
  <!-- div widget -->

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/timeago.js"></script>
    <script src="/js/main.js"></script>
  </body>
</html>