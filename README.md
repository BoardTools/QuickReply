QuickReply Reloaded
===================
Extended possibilities to use the QuickReply feature.

This is the package that you need to use to migrate from the old vendor.

## How to migrate from `Tatiana5` to `BoardTools` vendor safely?

### Using Upload Extensions method
Please note that the sequence of actions is important.

1. Upload [the files from this repository](https://github.com/Tatiana5/QuickReply/archive/migrate.zip) with Upload Extensions.
This step will save your data so that they will not be deleted on the third step.  
IMPORTANT: do NOT enable the extension and do NOT delete its data on this step.  
NOTE: the old version will be marked as `(old)`.
2. Navigate to the main page of Upload Extensions and upload [new files from BoardTools/QuickReply repository](https://github.com/BoardTools/QuickReply/archive/master.zip).
3. Click "Delete data" button for the previous QuickReply Reloaded extension (that is marked as `(old)`). If you have done the first step correctly, your data will not be lost.
4. Remove the old extension with Extension Cleaner tool that is available in Upload Extensions.
5. Enable new QuickReply Reloaded extension.

Done!

### Using standard method
Please note that the sequence of actions is important.

1. Disable the previous QuickReply Reloaded extension.
2. Delete all files from `ext/tatiana5/quickreply`.
3. Copy [all files from this repository](https://github.com/Tatiana5/QuickReply/archive/migrate.zip) to `ext/tatiana5/quickreply`.
This step will save your data so that they will not be deleted on the fifth step.
4. Upload [new files from BoardTools/QuickReply repository](https://github.com/BoardTools/QuickReply/archive/master.zip) to `ext/boardtools/quickreply`.
5. Click "Delete data" button for the previous QuickReply Reloaded extension. If you have done the third step correctly, your data will not be lost.
6. Remove the directory `ext/tatiana5/quickreply` with all its files from your server.
7. Enable new QuickReply Reloaded extension in the ACP.

Done!

## License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

© 2014 - 2015 Tatiana5 and LavIgor
