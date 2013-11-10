Шановний, <?php echo $user_data['last_name'].' '.$user_data['first_name']; ?>.
Ви отримали нове повідомлення.

Тема: <?php echo $subject; ?>

Текст повідомлення: 
<?php echo $message; ?>


Відправник: <?php echo $this->login_data['last_name'].' '.$this->login_data['first_name']; ?>.

Щоб відповісти на дане повідомлення, перейдіть за посиланням <?php echo $this->get_absolute_url(array($this->controller, 'messages')); ?>

З повагою, НФБ МЕДІА.