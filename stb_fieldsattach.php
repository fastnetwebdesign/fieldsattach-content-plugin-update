<?php


jimport('joomla.plugin.plugin');

JLoader::discover('stb_fieldsattach', __DIR__.'/helpers');

class plgContentStb_FieldsAttach extends JPlugin {

    // Look for the Joomla content event
    function onContentPrepare($context, &$article, &$params, $page = 0)
    {
        $this->onAddFieldsAttach($context, $article, $params, $page);
    }

    // custom event name for use in modules, startek etc
    function onAddFieldsAttach($context, &$article, &$params = null, $page = 0)
    {
        $article->fieldsAttach = new stb_fieldsattachAutoloader($context, $article, $params, $page);
    }

}

