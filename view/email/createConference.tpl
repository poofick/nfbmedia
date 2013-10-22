Шановний, <?php echo $user_data['last_name'].' '.$user_data['first_name']; ?>.
<?php if($type == conferenceModel::CONFERENCE_TYPE_PRIVATE): ?>
Вас запросили на конференцію "<?php echo $conference_data['title']; ?>".
<?php else: ?>
Створено публічну конференцію "<?php echo $conference_data['title']; ?>" і Ви за бажанням можете відвідати її.
<?php endif; ?>

Дата проведення: <?php echo $conference_data['estimated_start_time']; ?>.
Тривалість: <?php echo $conference_data['estimated_duration']; ?> хв.

З повагою, НФБ МЕДІА.