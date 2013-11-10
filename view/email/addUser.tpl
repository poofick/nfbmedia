Шановний, <?php echo $user_data['last_name'].' '.$user_data['first_name']; ?>.
Вас додано в систему НФБ МЕДІА.

Для входу в систему <?php echo $this->get_absolute_url(array($this->controller, 'login')); ?> використовуйте наступні дані:
Логін - <?php echo $user_data['email']; ?>

Пароль - <?php echo $user_data['password']; ?>


З повагою, НФБ МЕДІА.