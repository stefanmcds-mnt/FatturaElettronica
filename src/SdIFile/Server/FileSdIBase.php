<?php

namespace FatturaElettronica\SdIServer;

class FileSdIBase
{
    public $NomeFile = null;
    public $File = null;

    public function __construct(\StdClass $parametersIn = null)
    {
        if ($parametersIn) {
            if (!property_exists($parametersIn, 'NomeFile')) {
                throw new \Exception("Cannot find property 'NomeFile'");
            }
            if (!property_exists($parametersIn, 'File')) {
                throw new \Exception("Cannot find property 'File'");
            }

            $this->NomeFile = $parametersIn->NomeFile;
            $this->File = $parametersIn->File;
            $this->removeBOM();
        }
    }

    public function __toString()
    {
        return "NomeFile:{$this->NomeFile}";
    }

    public function import($file)
    {
        if (false === is_readable($file)) {
            throw new \Exception("'$file' not found or not readable");
        }

        $this->NomeFile = basename($file);
        $this->File = file_get_contents($file);
        $this->removeBOM();

        return $this;
    }

    /**
     * Remove UTF-8 BOM
     *
     * Credits: https://forum.italia.it/u/Francesco_Biegi
     * See https://forum.italia.it/t/risolto-notifica-di-scarto-content-is-not-allowed-in-prolog/5798/7
     */
    public function removeBOM()
    {
        $this->File = str_replace("\xEF\xBB\xBF", '', $this->File);

        return $this;
    }
}
