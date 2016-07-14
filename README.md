CWAC_Rights
===========

A rather simple permission library for PHP.

Description
-----------

If you want to use this in your own project, your job is to store a list of strings representing permissions for each user, group, role, item, sheep or whatever it is you require in your application.

You can apply a permission hierarchy by splitting your strings into distinct hierarchy sections, separated by periods - like so:

* admin.users.add
* admin.users.edit
* admin.users.delete
* admin.plugin.install
... you get the idea.

All permissions granted must be listed explicitly.

If you want to revoke a specific permission (like, if a user would have it by belonging to a group or role, but you don't like this guy very much,
so you revoke just this one permission to annoy him), you can prefix the permission name with a minus sign:

* -admin.users.edit

You can also use asterisks as wildcard after a period, to grant permission for all rights in this and any deeper hierarchy level:

* admin.users.*
(or, to revoke:)
* -admin.plugin.*

If you implement groups, roles or whatever else floats your boat, it's up to you how to do that and how to mash together the resulting permissions
array. All this library does is take the array of permissions, iterate through the hierarchy, check for revocations and spit out a boolean when
you call the checkRight() method with some permission name.

So in short... what this library is:

* a solid basis for a permission system
* dead simple

What this library is not:

* memory efficient
* elegant
* a complete solution that does not require you to do any work


The code dates back to 2007. It's been largely unchanged ever since and is still in production use today, so I consider it mostly stable.

Usage
-----

    $rights = array(
        'admin.*',
        '-admin.users.delete',
        'files.open',
        'files.download.pdf'
    );
    $user = new UserRights();
    $user->setRights($rights);
    echo "admin.honk (assumed true): ".(int)$user->checkRight('admin.something'), PHP_EOL;
    echo 'admin.users.delete (assumed false): '.(int)$user->checkRight('admin.users.delete'), PHP_EOL;
    echo 'files.download.pdf (assumed true): '.(int)$user->checkRight('files.download.pdf'), PHP_EOL;
    echo 'files.download.xls (assumed false): '.(int)$user->checkRight('files.download.xls'), PHP_EOL;
    echo 'files.open (assumed true): '.(int)$user->checkRight('files.open'), PHP_EOL;

For more usage examples, see the included unit test.


