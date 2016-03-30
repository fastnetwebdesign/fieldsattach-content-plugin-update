Install via the Joomla admin.

Activate the plugin in the `Extensions` > `Plugin Manager`. This is a `content` plugin.

Instead of `$name = fieldattach::getValue($this->item->id, 4, false);` use `$name = $this->item->fieldsAttach->getValueById(4);`

This plugin _should_ work on any com_content page, it uses the `onContentPrepare` event.

If you need to add fieldsAttach to a content module, e.g. mod_article_news or any other page where a JTableContent object exists then use the following code to add the fieldsAttach property

```
$article; // Assume your com_content article is in this variable

$context = '...'; // use this to describe the location you are in, e.g. "mod_article_news", "com_startek.details"

JPluginHelper::importPlugin('content');
$dispatcher = JEventDispatcher::getInstance();
// use "onAddFieldsAttach" not "onContentPrepare". Unless you really do want every other content plugin to act on this value.
$dispatcher->trigger('onAddFieldsAttach', array ($context, &$article /* Unused: , $params, $page */));
```

You will now have access to the `->fieldsAttach` property.

`$context` is not currently used (and neither are `$params` or `$page` which are the 3rd/4th parameters), but it would be wise to include a relevant context incase we need to know this information in a future version.
