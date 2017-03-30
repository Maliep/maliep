<?php 
  require "includes/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Блог IT_Минималиста!</title>

  <!-- Bootstrap Grid -->
  <link rel="stylesheet" type="text/css" href="/media/assets/bootstrap-grid-only/css/grid12.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

  <!-- Custom -->
  <link rel="stylesheet" type="text/css" href="/media/css/style.css">
</head>
<body>

  <div id="wrapper">

    <?php include "includes/HEADER.php"; ?>

    <?php
       $article = mysqli_query($connection, "SELECT * FROM `articles` WHERE `id` = " . (int) $_GET['id'] );
          if( mysqli_num_rows($article) <= 0 )
          {         
      ?>
       <div id="content">
      <div class="container">
        <div class="row">
          <section class="content__left col-md-8">
            <div class="block">
              <h3>Статья не найдена!</h3>
              <div class="block__content">
                <div class="full-text">
                Запрашиваемая вами статья не существует!
                </div>
              </div>
            </div>
            
          </section>
          <section class="content__right col-md-4">
            <?php include "includes/sidebar.php"; ?>
          </section>
        </div>
      </div>
    </div>
       <?php
    } else
    {  
      $art = mysqli_fetch_assoc($article);
      mysqli_query($connection, "UPDATE `articles` SET `views` = `views` + 1 WHERE `id` = " . (int) $art['id']);
       ?>
        <div id="content">
      <div class="container">
        <div class="row">
          <section class="content__left col-md-8">
            <div class="block">
            <a><?php echo $art['views'] ?> просмотров</a>
              <h3><?php echo $art['title'] ?></h3>
              <div class="block__content">
              <img src="/static/images/<?php echo $art['image']; ?>" style="max-width: 100%;">
                <div class="full-text"><?php echo $art['text'] ?></div>
              </div>
            </div>
            

  <div class="block">
    <a href="#comment-add-form">Добавить свой</a>
              <h3>Комментарии</h3>
              <div class="block__content">
                <div class="articles articles__vertical">
<?php
          $comments = mysqli_query($connection,"SELECT * FROM `comments` WHERE `article_id` = " . (int) $art['id'] .  " ORDER BY `id` DESC" );
                if(mysqli_num_rows($comments) <= 0){
                  echo 'Нет комментариев!';
                }
          while( $comment = mysqli_fetch_assoc($comments) )
          {
            ?> 
                  <article class="article">
                    <div class="article__image" style="background-image: url(https://www.gravatar.com/avatar/ <?php echo md5($comment['mail'] ); ?>?s=125);"></div>   
                    <div class="article__info">
                      <a href="/article.php?id=<?php echo $comment['article_id']; ?>"><?php echo $comment['author'];?></a>
                     <div class="article__info__meta"></div>
                      <div class="article__info__preview"><?php echo $comment['text']; ?></div>
                    </div>
                  </article>
                  <?php
                }
                ?>
                </div>
              </div>
              </div>

 <div class="block" id="comment-add-form">
              <h3>Добавить комментарий</h3>
              <div class="block__content">
                <form class="form" method="POST" action="/article.php?id= <?php echo $art['id']; ?>#comment-add-form" >
                <?php
                   if(isset($_POST['do_post']) )
                   {
                    $errors = array();
                    if($_POST['name'] == '')
                    {
                     $errors[] = 'Введите имя!';
                    }
                    if($_POST['nickname'] == '')
                    {
                     $errors[] = 'Введите Ваш Никнейм!';
                    }
                    if($_POST['mail'] == '')
                    {
                     $errors[] = 'Введите mail!';
                    }
                    if($_POST['text'] == '')
                    {
                     $errors[] = 'Введите текс комментария!';
                    }
                    if(empty($errors) )
                    {
                      //введите коммент
                     
                      mysqli_query($connection, "INSERT INTO `comments` (`author`,`nickname`,`mail`,`text`,`pubdate`,`article_id`) VALUES ('". $_POST['name']."','". $_POST['nickname']."','". $_POST['mail']."','". $_POST['text']."', NOW(),'". $art['id']."') ");

                       echo '<span style="color: green;font-weight: bold;margin-bottom: 10px;display: block;">Комментарий успешно добавлен!</span>';
                    } else 
                    {
                      //ВЫВЕСТИ ОШИБКУ
                      echo '<span style="color: red;font-weight: bold;margin-bottom: 10px;display: block;"> ' . $errors['0'] . '</span>';
                    }
                  }

                  ?>

                 <div class="form__group">
                    <div class="row">
                      <div class="col-md-4">
                        <input type="text" name="name" class="form__control" placeholder="Имя" value="<?php echo $_POST['name']; ?> ">
                      </div>
                      <div class="col-md-4">
                        <input type="text" name="nickname" class="form__control" placeholder="Никнейм" value="<?php echo $_POST['nickname']; ?> ">
                      </div>
                       <div class="col-md-4">
                        <input type="text" name="mail" class="form__control" placeholder="mail (не будет показан)" value="<?php echo $_POST['mail']; ?> ">
                    </div>
                  </div>
               </div>
                  <div class="form__group">
                    <textarea name="text"  class="form__control" placeholder="Текст комментария ..."><?php echo $_POST['text']; ?></textarea>
                  </div>
                  <div class="form__group">
                    <input type="submit" class="form__control" name="do_post" value="Добавить комментарий">
                  </div>
                </form>
              </div>
            </div>
          </section>
          <section class="content__right col-md-4">
            <?php include "includes/sidebar.php"; ?>
          </section>
        </div>
      </div>
    </div>
      
       <?php 
       } 
        ?>

   

   
<?php include "includes/folder.php"; ?>
  </div>

</body>
</html>