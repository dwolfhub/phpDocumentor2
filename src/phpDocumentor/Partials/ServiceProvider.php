<?php
declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2018 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Partials;

use Cilex\Application;
use phpDocumentor\Configuration as ApplicationConfiguration;
use phpDocumentor\Partials\Collection as PartialsCollection;
use phpDocumentor\Translator\Translator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * This provider is responsible for registering the partials component with the given Application.
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * @param Application $app An Application instance
     *
     * @throws Exception\MissingNameForPartialException if a partial has no name provided.
     */
    public function register(Container $app)
    {
        /** @var Translator $translator */
        $translator = $app['translator'];
        $translator->addTranslationFolder(__DIR__ . DIRECTORY_SEPARATOR . 'Messages');

        /** @var ApplicationConfiguration $config */
        $config = $app['config'];

        $app['markdown'] = function () {
            return \Parsedown::instance();
        };

        $partialsCollection = new PartialsCollection($app['markdown']);
        $app['partials'] = $partialsCollection;

        /** @var Partial[] $partials */
        $partials = []; //$config->getPartials();
        if ($partials) {
            foreach ($partials as $partial) {
                if (! $partial->getName()) {
                    throw new Exception\MissingNameForPartialException('The name of the partial to load is missing');
                }

                $content = '';
                if ($partial->getContent()) {
                    $content = $partial->getContent();
                } elseif ($partial->getLink()) {
                    if (! is_readable($partial->getLink())) {
                        $app['monolog']->error(
                            sprintf($translator->translate('PPCPP:EXC-NOPARTIAL'), $partial->getLink())
                        );
                        continue;
                    }

                    $content = file_get_contents($partial->getLink());
                }

                $partialsCollection->set($partial->getName(), $content);
            }
        }
    }
}
