<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace FAPI\PhraseApp\Api;

use FAPI\PhraseApp\Model\Locale\Uploaded;
use FAPI\PhraseApp\Model\Translation\LocaleIndex;
use FAPI\PhraseApp\Model\Translation\TranslationCreated;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 */
class Translation extends HttpApi
{
    /**
     * Index a locale
     *
     * @param string    $projectKey
     * @param string    $localeId
     * @param array     $params
     * @return mixed|ResponseInterface
     */
    public function indexLocale(string $projectKey, string $localeId, array $params)
    {
        $response = $this->httpGet(sprintf('/api/v2/projects/%s/locales/%s/translations', $projectKey, $localeId), $params);

        if (!$this->hydrator) {
            return $response;
        }

        if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, LocaleIndex::class);
    }

    /**
     * Create a translation
     *
     * @param string    $projectKey
     * @param string    $keyId
     * @param string    $content
     * @param array     $params
     * @return mixed|ResponseInterface
     */
    public function create(string $projectKey, string $keyId, string $content, array $params)
    {
        $params['key_id'] = $keyId;
        $params['content'] = $content;

        $response = $this->httpPost(sprintf('/api/v2/projects/%s/translations', $projectKey), $params);

        if (!$this->hydrator) {
            return $response;
        }

        if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, TranslationCreated::class);
    }
}
