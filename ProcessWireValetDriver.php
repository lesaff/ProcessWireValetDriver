<?php

class ProcessWireValetDriver extends BasicValetDriver
{
    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return bool
     */
    public function serves($sitePath, $siteName, $uri)
    {
        return is_dir($sitePath.'/public/wire');
    }


    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {

        $_SERVER['SERVER_NAME']     = $_SERVER['HTTP_HOST'];
        $_SERVER['SCRIPT_NAME']     = '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = $sitePath.'/index.php';
        $_GET['it']                 = $uri;

        if (strpos($_SERVER['REQUEST_URI'], '/index.php') === 0) {
            $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 10);
        }

        if (strpos($_SERVER['REQUEST_URI'], '/public/index.php') === 0) {
            $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 10);
        }

        if ($uri === '') {
            $uri = '/';
        }

        if (file_exists($indexPath = $sitePath.'/install.php')) {
            return $indexPath;
        }

        if (file_exists($indexPath = $sitePath.'/public/install.php')) {
            return $indexPath;
        }

        if (file_exists($indexPath = $sitePath.'/index.php')) {
            return parent::frontControllerPath(
                $sitePath, $siteName, $this->forceTrailingSlash($uri)
            );
        }

        if (file_exists($indexPath = $sitePath.'/public/index.php')) {
            return parent::frontControllerPath(
                $sitePath, $siteName, $this->forceTrailingSlash($uri)
            );
        }

    }


    /**
     * Redirect to uri with trailing slash.
     *
     * @param  string $uri
     * @return string
     */
    private function forceTrailingSlash($uri)
    {
        if (substr($uri, -1 * strlen('/admin')) == '/admin') {
            header('Location: '.$uri.'/'); die;
        }
        return $uri;
    }

}
