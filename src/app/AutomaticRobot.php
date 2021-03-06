<?php

namespace Project\App;

use Project\Model\IModel;
use Project\Model\Page;

class AutomaticRobot
{
	/**
	 * @var IModel
	 */
	private $model;

	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * @var \Curl
	 */
	private $curl;



	public function __construct(IModel $model, Parser $parser, \Curl $curl)
	{
		$this->model = $model;
		$this->parser = $parser;
		$this->curl = $curl;
	}



	public function run()
	{
		$pageURL = $this->getUrl();
		$pageHTML = $this->curl->get($pageURL);
		$emails = $this->parser->getEmails($pageHTML);
		$countEmails = count($emails);
		$URLs = $this->parser->getURLs($pageHTML);
		$this->model->saveEmails($emails);
		$this->model->saveURLs($URLs);

		echo "Download emails from address '$pageURL' is complete.\n";
		echo "There are $countEmails records.\n";
	}



	private function getUrl()
	{
		$pageURL = $this->model->nextPage();
		if (empty($pageURL)) {
			echo "List of web address is empty.\n";
			exit;
		}

		return $pageURL;
	}
}
