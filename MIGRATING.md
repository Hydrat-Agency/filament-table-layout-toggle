# Migration guide

This doc will help you migrating to newer version of the package.

## 1.x => 2.x

In the version 2, we migrated static configuration for the local storage to persister classes. This change was made to allow more flexibility and to support multiple storage types. In order to migrate your configuration, you need to update the `config/filament-table-layout-toggle.php` file or the Plugin initialization definition (if using panels) :

Using a configuration file:

```diff
return [
    ...

    'persist' => [
-        'enabled' => true,
+        'persister' => \Hydrat\TableLayoutToggle\Persisters\LocalStoragePersister::class,
    ],
];
```

Using panels:

```diff
use Hydrat\TableLayoutToggle\Persisters\LocalStoragePersister;

\->plugins([
    TableLayoutTogglePlugin::make()
-       ->persistLayoutInLocalStorage()
+       ->persistLayoutUsing(LocalStoragePersister::class)
        ->shareLayoutBetweenPages()
        ->displayToggleAction(),
])
```

If you were using customizations on the cache key, you now need to change the component class to configure the persister instead :

```diff
class ListEntries extends ListRecords
{
-    protected function persistToggleStatusName(): string
-    {
-        return 'tableLayoutView::listUsersTable';
-    }


+    public function configurePersister(): void
+    {
+        $this->layoutPersister->setKey('tableLayoutView::listUsersTable');
+    }
}
```

If you were disabling the persister on a table using `persistToggleEnabled()` method, you should now setup a `DisabledPersister` instead :

```diff
class ListEntries extends ListRecords
{
-    protected function persistToggleEnabled(): bool
-    {
-        return false;
-    }


+    public function configurePersister(): void
+    {
+        $this->layoutPersister = new \Hydrat\TableLayoutToggle\Persisters\DisabledPersister($this);
+    }
}
```
