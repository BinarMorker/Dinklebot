<?php

class DatabaseRequest {

	private $database;
	private $query;
	private $parameters;
	private $count;
	private $result;

	public function __construct($database, $query, $parameters) {
		if (empty($this->database)) {
			try {
				$this->database = $database;
				$this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->query = $query;
				$this->parameters = $parameters;
			} catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	public function send() {
		if (!empty($this->database)) {
			try {
				$statement = $this->database->prepare($this->query);
				$statement->execute($this->parameters);
				$this->count = $statement->rowCount();
				$statement->closeCursor();
			} catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	public function receive() {
		if (!empty($this->database) && empty($this->result)) {
			try {
				$statement = $this->database->prepare($this->query);
				$statement->execute($this->parameters);
				$this->count = $statement->rowCount();
				@$this->result = $statement->fetchAll(PDO::FETCH_ASSOC);
				$statement->closeCursor();
			} catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
	}

	public function get_count() {
		if (!empty($this->count)) {
			return $this->count;
		}
	}

	public function get_result() {
		if (!empty($this->result)) {
			return $this->result;
		}
	}

	public static function init($host, $database, $username, $password) {
		return new PDO('mysql:host='.$host.';dbname='.$database.';charset=utf8', $username, $password);
	}

}