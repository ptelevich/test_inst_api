<?php
namespace model;

/**
 * Class UserInfo
 * @package model
 */
class UserInfo
{
    /**
     * @var
     */
    private $_data;
    /**
     * @var
     */
    private $_counts;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->_data = $data;

        $this->_counts['media'] = isset($this->_data->counts->media) ? $this->_data->counts->media : '';
        $this->_counts['followed_by'] = isset($this->_data->counts->followed_by) ? $this->_data->counts->followed_by : '';
        $this->_counts['follows'] = isset($this->_data->counts->follows) ? $this->_data->counts->follows : '';

        $this->_data->counts = (object)$this->_counts;
    }

    /**
     * @param $name
     * @return string
     */
    public function __get($name)
    {
        return isset($this->_data->{$name}) ? $this->_data->{$name} : '';
    }
}
