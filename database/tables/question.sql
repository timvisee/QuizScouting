CREATE TABLE `quiz_question` (
	`q_id` INT(11) NOT NULL AUTO_INCREMENT,
	`q_q` TEXT NOT NULL,
	`q_a` TEXT NOT NULL,
	`q_b` TEXT NOT NULL,
	`q_c` TEXT NOT NULL,
	`q_d` TEXT NOT NULL,
	`q_correct` VARCHAR(1) NOT NULL,
	PRIMARY KEY (`q_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
