<?php

namespace App\WsApogeeBundle\DependencyInjection;

class WebService
{
	protected $url;
	protected $endpoint;
	protected $client;

	/**
	 * Si vrai, les appels à soapCall retourne directement l'objet contenu dans la réponse
	 * Si faut, retourne la reponse complete
	 * @var bool
	 */
	protected $fast_response;

	public function __construct(string $url, string $endpoint, array $params = [])
	{
		$this->url = $url;
		$this->endpoint = $endpoint;
		$this->client = new \SoapClient($this->getFullUrl(), $params);
	}

	public function isFastResponse(): bool
	{
		return $this->fast_response;
	}

	public function setFastResponse(bool $fast_response): void
	{
		$this->fast_response = $fast_response;
	}

	protected function getFullUrl(): string
	{
		return $this->url . $this->endpoint;
	}

	protected function soapCall(string $func_name, array $params)
	{
		$resp = $this->client->__soapCall($func_name, array($params));
		return $this->fast_response ? current($resp) : $resp;
	}
}