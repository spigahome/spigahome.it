<?php
/**
 * ZooLogger
 */
class ZooLogger
{
    const INFO = 'info';
    const DEBUG = 'debug';
    const ALERT = 'alert';
    const ERROR = 'error';
    const NOTICE = 'notice';
    const WARNING = 'warning';
    const SUCCESS = 'success';
    const CRITICAL = 'critical';
    const EMERGENCY = 'emergency';

    /**
    * Log
    *
    * @var  string
    */
    protected $log;

    /**
    * Constructor
    */
    function __construct()
    {
        $this->log = '';
    }

    /**
    * Logs with an arbitrary level.
    *
    * @param  string  $level
    * @param  string  $message
    */
    function log($level, $message, $timestamp = true)
    {
        $message = (string)$message;
        $timestamp = $timestamp ? '[' . date('Y-m-d H:i:s A', time()) . '] ' : '';

        switch ($level) {
            case self::INFO:
                $this->log .= '<p class="log-message info">' . $timestamp . '<strong class="log-level" style="color:blue">' . esc_html__('Info', 'clever-addons') . '</strong>: ' . $message . '</p>';
            break;
            case self::DEBUG:
                $this->log .= '<p class="log-message debug">' . $timestamp . '<strong class="log-level" style="color:teal">' . esc_html__('Debug', 'clever-addons') . '</strong>: ' . $message . '</p>';
            break;
            case self::ALERT:
                $this->log .= '<p class="log-message alert">' . $timestamp . '<strong class="log-level" style="color:orange">' . esc_html__('Alert', 'clever-addons') . '</strong>: ' . $message . '</p>';
            break;
            case self::ERROR:
                $this->log .= '<p class="log-message error">' . $timestamp . '<strong class="log-level" style="color:red">' . esc_html__('Error', 'clever-addons') . '</strong>: ' . $message . '</p>';
            break;
            case self::NOTICE:
                $this->log .= '<p class="log-message notice">' . $timestamp . '<strong class="log-level" style="color:blue">' . esc_html__('Notice', 'clever-addons') . '</strong>: ' . $message . '</p>';
            break;
            case self::WARNING:
                $this->log .= '<p class="log-message warning">' . $timestamp . '<strong class="log-level" style="color:red">' . esc_html__('Warning', 'clever-addons') . '</strong>: ' . $message . '</p>';
            break;
            case self::SUCCESS:
                $this->log .= '<p class="log-message success">' . $timestamp . '<strong class="log-level" style="color:green">' . esc_html__('Success', 'clever-addons') . '</strong>: ' . $message . '</p>';
            break;
            case self::CRITICAL:
                $this->log .= '<p class="log-message crictical">' . $timestamp . '<strong class="log-level" style="color:red">' . esc_html__('Critical', 'clever-addons') . '</strong>: ' . $message . '</p>';
            break;
            case self::EMERGENCY:
                $this->log .= '<p class="log-message emergency">' . $timestamp . '<strong class="log-level" style="color:red">' . esc_html__('Emergency', 'clever-addons') . '</strong>: ' . $message . '</p>';
            break;
            default:
                $this->log .= '<p class="log-message notice">' . $timestamp . '<strong class="log-level" style="color:blue">' . esc_html__('Notice', 'clever-addons') . '</strong>: ' . $message . '</p>';
        }
    }

    /**
    * get log message
    */
    function __toString()
    {
        return $this->log;
    }
}
