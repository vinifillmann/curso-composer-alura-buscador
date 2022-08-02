<?php

namespace Alura\BuscadorDeCursos;

use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class Buscador
{
    private ClientInterface $client;
    private Crawler $crawler;


    public function __construct(ClientInterface $httpClient, Crawler $crawler)
    {
        $this->client = $httpClient;
        $this->crawler = $crawler;
    }

    public function buscar(string $url): array
    {
        $resposta = $this->client->request("GET", $url);
        $html = $resposta->getBody();
        $this->crawler->addHtmlContent($html);
        $elementosPassos = $this->crawler->filter("p.formacao-passo-nome");
        $cursos = [];
        foreach ($elementosPassos as $passo) {
            $cursos[] = str_replace(["\n"], [" - "], $passo->textContent);
        }
        return $cursos;
    }
}
