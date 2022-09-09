<?php

namespace App\Helper;

use Illuminate\Support\Str;

trait CaseStylesHelper
{
    protected $attrib, $alternative, $replaceNull;

    public function caseStyleReplaceNull(bool $replace)
    {
        $this->replaceNull = $replace;
        return $this;
    }

    /**
     *
     * @param $to
     * @param array | object $data
     * @param string $attrib
     * @param false $alternative
     * @return mixed
     */
    public function convertCaseStyle($to, $data, $attrib = 'key', $alternative = false)
    {
        $this->replaceNull = false;
        $this->attrib = $attrib;
        $this->alternative = $alternative;

        return $this->$to($data);
    }

    /**
     *
     * @param $datas
     * @return array
     */
    protected function camelCase($datas)
    {
        $result = [];

        if (is_array($datas)) {
            $datas = collect($datas)->toArray();
            foreach ($datas as $indexData => $data) {
                $result[$indexData] = $this->subArray($data, 'camelCase');
            }

            return $result;
        } else {
            if (is_object($datas)) {
                $datas = collect($datas)->toArray();
                $result = $this->subArray($datas, 'camelCase');
            } else {
                $result = Str::camel($datas);
            }
        }

        return $result;
    }

    /**
     *
     * @param $data
     * @return array|int|string
     */
    protected function snakeCase($data)
    {
        if ($this->alternative) {
            $this->alternative = false;
            $result = [];
            foreach ($data as $indexItem => $item) {
                $result[$indexItem] = $this->subArray($item, 'snakeCase');
            }

            return $result;
        }

        if (is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $key = is_numeric($key) ? $key : ($this->attrib === 'key' ? Str::snake($key) : $key);

                if (is_array($value)) {
                    $result[$key] = $this->snakeCase($value);
                } else {
                    $value = ($this->attrib === 'key' ? $value : Str::snake($value));
                    $result[$key] = $this->replaceNull ? ($value ?? '') : $value;
                }
            }

            return $result;
        } else {
            return is_numeric($data) ? $data : Str::snake($data);
        }
    }

    /**
     *
     * @param $data
     * @param string $convert
     * @return array
     */
    protected function subArray($data, string $convert)
    {
        $result = [];
        if (is_array($data)) {
            foreach ($data as $keyData => $value) {
                $keyData = is_numeric($keyData) ? $keyData : ($this->attrib === 'key' ? $this->$convert($keyData) : $keyData);

                if (is_array($value)) {
                    $result[$keyData] = $this->subArray($value, $convert);
                } else {
                    $value = $this->attrib === 'key' ? $value : ($convert === 'snakeCase' ? Str::snake($value) : Str::camel($value));

                    $result[$keyData] = $this->replaceNull ? ($value ?? '') : $value;
                }
            }
        } else {
            $value = is_numeric($data) ? $data : ($this->attrib === 'key' ? $data : ($convert === 'snakeCase' ? Str::snake($data) : Str::camel($data)));
            $result = $value;
        }

        return $result;
    }

    /**
     *
     * @param $string
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        $result = str_replace('_', '', ucwords($string, '_'));
        $result = lcfirst($result);

        return $result;
    }

    /**
     *
     * @param $string
     * @return string
     */
    protected function convertToSnakeCase($string)
    {
        if (preg_match('/[A-Z]/', $string)) {
            $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';

            preg_match_all($pattern, $string, $matches);
            $result = $matches[0];

            foreach ($result as &$match) {
                $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
            }

            $result = implode('_', $result);

            return $result;
        }

        return $string;
    }
}
