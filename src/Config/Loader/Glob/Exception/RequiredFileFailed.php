<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Loader\Glob\Exception;

use Exception;
use ExtendsFramework\ServiceLocator\Config\Loader\LoaderException;

class RequiredFileFailed extends Exception implements LoaderException
{
    /**
     * When $file is a none existing file.
     *
     * @param string $file
     */
    public function __construct(string $file)
    {
        parent::__construct(sprintf(
            'Glob loader failed to require file "%s".',
            $file
        ));
    }
}
