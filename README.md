# phpBB QuickReply Extension

This is the repository for the development of the phpBB QuickReply Extension.

[![Build Status](https://travis-ci.org/Tatiana5/QuickReply.svg?branch=master)](https://travis-ci.org/Tatiana5/QuickReply)

## Quick Install
You can install this on the latest copy of the develop branch ([phpBB 3.1-dev](https://github.com/phpbb/phpbb3)) by following the steps below:

1. [Download the latest release](https://github.com/Tatiana5/QuickReply).
2. Unzip the downloaded release, and change the name of the folder to `quickreply`.
3. In the `ext` directory of your phpBB board, create a new directory named `tatiana5` (if it does not already exist).
4. Copy the `quickreply` folder to `phpBB/ext/tatiana5/` (if done correctly, you'll have the main extension class at (your forum root)/ext/tatiana5/quickreply/ext.php).
5. Navigate in the ACP to `Customise -> Manage extensions`.
6. Look for `QuickReply` under the Disabled Extensions list, and click its `Enable` link.
7. Configure QuickReply by navigating in the ACP to `Extensions` -> `QuickReply`.

## Uninstall

1. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
2. Look for `QuickReply` under the Enabled Extensions list, and click its `Disable` link.
3. To permanently uninstall, click `Delete Data` and then delete the `/ext/tatiana5/quickreply` folder.

## License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)
