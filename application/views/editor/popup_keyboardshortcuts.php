<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
        <title>Keyboard Shortcuts</title>

        <link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
        <link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
        <link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
        <script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>core/js/sceditormisc.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function(e) {
                $('body').css({'overflow': 'hidden'}).addClass('keyboardshortcuts');
<?php if (isset($message)) { ?>
                    parent.sceditor.call("base.notify()", {msg: '<?php echo $message; ?>', 'type': 'success'});
                    parent.sceditor.call("base.command.reload()", <?php echo json_encode($commands); ?>);
<?php } ?>
                var keyman = function() {
                    $(".shortcut-key").keydown(function(ev) {
                        keyman.keydown(ev.keyCode);
                        keyman.__target = this;
                        if (keyman.process(ev, ev.keyCode)) {
                            ev.preventDefault();
                            ev.cancelBubble = true;
                            return false;
                        }
                    });
                    $(".shortcut-key").keyup(function(ev) {
                        keyman.clear();
                    });
                    $(window).focus(function(ev) {
                        keyman.clear();
                    });

                    var mixin = function(obj, mixin) {
                        for (var key in mixin) {
                            obj[key] = mixin[key];
                        }
                        return obj;
                    };
                    var Keys = (function() {
                        var ret = {
                            MODIFIER_KEYS: {
                                16: 'Shift', 17: 'Ctrl', 18: 'Alt', 224: 'Meta'
                            },
                            KEY_MODS: {
                                "Ctrl": 1, "Alt": 2, "Option": 2, "Shift": 4,
                                "Super": 8, "Meta": 8, "Command": 8, "Cmd": 8
                            },
                            FUNCTION_KEYS: {
                                8: "Backspace",
                                9: "Tab",
                                13: "Return",
                                19: "Pause",
                                27: "Esc",
                                32: "Space",
                                33: "PageUp",
                                34: "PageDown",
                                35: "End",
                                36: "Home",
                                37: "Left",
                                38: "Up",
                                39: "Right",
                                40: "Down",
                                44: "Print",
                                45: "Insert",
                                46: "Delete",
                                96: "Numpad0",
                                97: "Numpad1",
                                98: "Numpad2",
                                99: "Numpad3",
                                100: "Numpad4",
                                101: "Numpad5",
                                102: "Numpad6",
                                103: "Numpad7",
                                104: "Numpad8",
                                105: "Numpad9",
                                '-13': "NumpadEnter",
                                112: "F1",
                                113: "F2",
                                114: "F3",
                                115: "F4",
                                116: "F5",
                                117: "F6",
                                118: "F7",
                                119: "F8",
                                120: "F9",
                                121: "F10",
                                122: "F11",
                                123: "F12",
                                144: "Numlock",
                                145: "Scrolllock"
                            },
                            PRINTABLE_KEYS: {
                                32: ' ', 48: '0', 49: '1', 50: '2', 51: '3', 52: '4', 53: '5',
                                54: '6', 55: '7', 56: '8', 57: '9', 59: ';', 61: '=', 65: 'A',
                                66: 'B', 67: 'C', 68: 'D', 69: 'E', 70: 'F', 71: 'G', 72: 'H',
                                73: 'I', 74: 'J', 75: 'K', 76: 'L', 77: 'M', 78: 'N', 79: 'O',
                                80: 'P', 81: 'Q', 82: 'R', 83: 'S', 84: 'T', 85: 'U', 86: 'V',
                                87: 'W', 88: 'X', 89: 'Y', 90: 'Z', 107: '+', 109: '-', 110: '.',
                                187: '=', 188: ',', 189: '-', 190: '.', 191: '/', 192: '`', 219: '[',
                                220: '\\', 221: ']', 222: '\''
                            }
                        };
                        var name, i;
                        for (i in ret.FUNCTION_KEYS) {
                            name = ret.FUNCTION_KEYS[i].toLowerCase();
                            ret[name] = parseInt(i, 10);
                        }
                        for (i in ret.PRINTABLE_KEYS) {
                            name = ret.PRINTABLE_KEYS[i].toLowerCase();
                            ret[name] = parseInt(i, 10);
                        }
                        mixin(ret, ret.MODIFIER_KEYS);
                        mixin(ret, ret.PRINTABLE_KEYS);
                        mixin(ret, ret.FUNCTION_KEYS);
                        ret.enter = ret["return"];
                        ret.escape = ret.esc;
                        ret.del = ret["delete"];
                        ret[173] = '-';

                        (function() {
                            var mods = ["Cmd", "Ctrl", "Alt", "Shift"];
                            for (var i = Math.pow(2, mods.length); i--; ) {
                                ret.KEY_MODS[i] = mods.filter(function(x) {
                                    return i & ret.KEY_MODS[x];
                                }).join("-") + "-";
                            }
                        })();

                        ret.KEY_MODS[0] = "";
                        ret.KEY_MODS[-1] = "input";

                        return ret;
                    })();

                    var os = (navigator.platform.match(/mac|win|linux/i) || ["other"])[0].toLowerCase();
                    var ua = navigator.userAgent;
                    var isWin = (os == "win");
                    if (isWin) {
                        $(".shortcut-key-mac").hide();
                    }
                    var isMac = (os == "mac");
                    if (isMac) {
                        $(".shortcut-key-win").hide();
                    }
                    var isLinux = (os == "linux");
                    var isIE =
                        (navigator.appName == "Microsoft Internet Explorer" || navigator.appName.indexOf("MSAppHost") >= 0)
                        ? parseFloat((ua.match(/(?:MSIE |Trident\/[0-9]+[\.0-9]+;.*rv:)([0-9]+[\.0-9]+)/) || [])[1])
                        : parseFloat((ua.match(/(?:Trident\/[0-9]+[\.0-9]+;.*rv:)([0-9]+[\.0-9]+)/) || [])[1]); // for ie

                    var isOldIE = isIE && isIE < 9;
                    var isGecko = isMozilla = (window.Controllers || window.controllers) && window.navigator.product === "Gecko";
                    var isOldGecko = isGecko && parseInt((ua.match(/rv\:(\d+)/) || [])[1], 10) < 4;
                    var isOpera = window.opera && Object.prototype.toString.call(window.opera) == "[object Opera]";
                    var isWebKit = parseFloat(ua.split("WebKit/")[1]) || undefined;

                    var isChrome = parseFloat(ua.split(" Chrome/")[1]) || undefined;

                    var isAIR = ua.indexOf("AdobeAIR") >= 0;

                    var isIPad = ua.indexOf("iPad") >= 0;

                    var isTouchPad = ua.indexOf("TouchPad") >= 0;

                    var isChromeOS = ua.indexOf(" CrOS ") >= 0;

                    var ts = 0;
                    var pressedKeys = null;
                    return {
                        __target: null,
                        keyCodeToString: function(keyCode) {
                            var keyString = Keys[keyCode];
                            if (typeof keyString != "string") {
                                keyString = String.fromCharCode(keyCode);
                            }
                            //return keyString.toLowerCase();
                            return keyString;
                        },
                        keydown: function(keyCode) {
                            if (pressedKeys == null) {
                                pressedKeys = [];
                            }
                            pressedKeys[keyCode] = true;
                        },
                        clear: function() {
                            pressedKeys = null;
                            this.__target = null;
                        },
                        process: function(e, keyCode) {
                            var hashId = this.getModifierHash(e);

                            if (!isMac && pressedKeys) {
                                if (pressedKeys[91] || pressedKeys[92])
                                    hashId |= 8;
                                if (pressedKeys.altGr) {
                                    if ((3 & hashId) != 3)
                                        pressedKeys.altGr = 0;
                                    else
                                        return;
                                }
                                if (keyCode === 18 || keyCode === 17) {
                                    var location = "location" in e ? e.location : e.keyLocation;
                                    if (keyCode === 17 && location === 1) {
                                        ts = e.timeStamp;
                                    } else if (keyCode === 18 && hashId === 3 && location === 2) {
                                        var dt = -ts;
                                        ts = e.timeStamp;
                                        dt += ts;
                                        if (dt < 3)
                                            pressedKeys.altGr = true;
                                    }
                                }
                            }

                            if (keyCode in Keys.MODIFIER_KEYS) {
                                switch (Keys.MODIFIER_KEYS[keyCode]) {
                                    case "Alt":
                                        hashId = 2;
                                        break;
                                    case "Shift":
                                        hashId = 4;
                                        break;
                                    case "Ctrl":
                                        hashId = 1;
                                        break;
                                    default:
                                        hashId = 8;
                                        break;
                                }
                                keyCode = -1;
                            }

                            if (hashId & 8 && (keyCode === 91 || keyCode === 93)) {
                                keyCode = -1;
                            }

                            if (!hashId && keyCode === 13) {
                                var location = "location" in e ? e.location : e.keyLocation;
                                if (location === 3) {
                                    return this.callback(e, hashId, -keyCode);
                                    if (true/*e.defaultPrevented*/)
                                        return;
                                }
                            }

                            if (isChromeOS && hashId & 8) {
                                return this.callback(e, hashId, keyCode);
                                if (true/*e.defaultPrevented*/)
                                    return;
                                else
                                    hashId &= ~8;
                            }
                            if (!hashId && !(keyCode in Keys.FUNCTION_KEYS) && !(keyCode in Keys.PRINTABLE_KEYS)) {
                                return false;
                            }

                            return this.callback(e, hashId, keyCode);
                        },
                        callback: function(e, hashId, keyCode) {
                            /*
                             if (keyCode == -1) {
                             return false;
                             }*/

                            var key = Keys.KEY_MODS[hashId] + (keyCode != -1 ? this.keyCodeToString(keyCode) : '');
                            if (this.__target) {
                                if (keyCode == 8) {
                                    this.__target.value = "";
                                } else {
                                    this.__target.value = key;
                                }
                                return true;
                            }

                            return false;
                        },
                        getModifierHash: isMac && isOpera && !("KeyboardEvent" in window)
                            ? function(e) {
                                return 0 | (e.metaKey ? 1 : 0) | (e.altKey ? 2 : 0) | (e.shiftKey ? 4 : 0) | (e.ctrlKey ? 8 : 0);
                            }
                        : function(e) {
                            return 0 | (e.ctrlKey ? 1 : 0) | (e.altKey ? 2 : 0) | (e.shiftKey ? 4 : 0) | (e.metaKey ? 8 : 0);
                        }
                    };
                }();
            });

            function cancel() {
                parent.scpopupclose();
            }

            function save() {
                $("#command-form").submit();
            }

            function restore() {
                $("<input type='hidden' name='restore' value='1'>").appendTo('#command-form');
                $("#command-form").submit();
            }

        </script>

        <style>
            html, body {
                height: 100%;
            }
            body {
                margin: 0;
                padding: 0;

                -webkit-box-sizing: border-box;
                -moz-box-sizing: 	border-box;
                box-sizing: 		border-box;
            }

            * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: 	border-box;
                box-sizing: 		border-box;
            }
        </style>
    </head>
    <body data-width='100%' data-height='100%' data-controls="{'Restore Default Keybindings':'restore','Save Configuration':'save','Close':'close'}">
        <div class="infopopup" style="height: 100%;">
            <form id="command-form" action="<?php echo base_url() . 'command/' ?>save" method="post" class="" style="height: 100%;">
                <div class="infooptionscontainer" style="padding-right: 0; height: 100%; overflow: hidden;"> <!-- -->

                    <?php /* Tab Header: Start */ ?>
                    <div class="tabsectionheader">
                        <div class="tabsectionheader_inside">


                            <div class="clear"></div>
                        </div>

                        <div class="tabsectionheadersep"><span></span></div>

                        <div class="clear"></div>
                    </div>
                    <?php /* Tab Header: End */ ?>

                    <?php /* Single Column Wrapper: Start */ ?>
                    <div class="sectionwrapper">
                        <div class="sectionholder_inside">
                            <div class="sectionitems">

                                <div class="infopopuptitle">
                                    <span>Keybindings</span>
                                </div>

                                <?php /* Keybindings: Start */ ?>
                                <div class="keybindingswrapper">
                                    <div class="keybindings_inside">

                                        <ul class="keybindingslist">

                                            <?php /* Header: Start */ ?>
                                            <li class="keybindingsheader">
                                                <div class="keybindingsheadercols">
                                                    <div class="keybindingsheaderlabel">Name</div>
                                                    <div class="keybindingsheaderkey win shortcut-key-win">Keystroke (Win)</div>
                                                    <div class="keybindingsheaderkey mac shortcut-key-mac">Keystroke (Mac)</div>
                                                    <div class="keybindingsheaderdesc">Description</div>

                                                    <div class="clear"></div>
                                                </div>
                                            </li>
                                            <?php /* Header: End */ ?>

                                            <?php
                                            if (sizeof($types) > 0) {
                                                foreach ($types as $type) {
                                                    ?>
                                                    <li class="keybindingssec">
                                                        <div class="keybindingssec_inside">
                                                            <div class="keybindingssec_title">
                                                                <span class="bktitleicon"></span>
                                                                <span class="kbtlabel"><?php echo $type->command_type_name; ?></span>
                                                            </div>

                                                            <?php
                                                            if (sizeof($commands) > 0) {
                                                                foreach ($commands as $command) {
                                                                    if ($command->type_id != $type->command_type_id) {
                                                                        continue;
                                                                    }
                                                                    ?>
                                                                    <div class="keybindingssecitem">
                                                                        <input type="hidden" name="names[]" value="<?php echo $command->name; ?>" />
                                                                        <div class="keybindingssecitemlabel"><?php echo $command->title; ?></div>
                                                                        <div class="keybindingssecitemkey win shortcut-key-win">
                                                                            <input class="shortcut-key text" type="text" id="" name="shortcut_keys[]" value="<?php echo $command->shortcut_key; ?>" />
                                                                        </div>
                                                                        <div class="keybindingssecitemkey mac shortcut-key-mac">
                                                                            <input class="shortcut-key text" type="text" id="" name="shortcut_key_macs[]" value="<?php echo $command->shortcut_key_mac; ?>" />
                                                                        </div>
                                                                        <div class="keybindingssecitemdesc"><?php echo $command->desc; ?></div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </ul>

                                        <div class="clear"></div>
                                    </div>

                                    <div class="clear"></div>
                                </div>
                                <?php /* Keybindings: End */ ?>

                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>
                    <?php /* Single Column Wrapper: End */ ?>

                    <div class="clear"></div>
                </div>
            </form>

            <div class="clear"></div>
        </div>
    </body>
</html>