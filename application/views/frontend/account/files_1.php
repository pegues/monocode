<?php
/*
  Template Name : My Account (File Explorer)
 */
?>

<link rel="stylesheet" href="<?php echo base_url(); ?>themes/frontend/scriptscoder/css/filemanager.css" rel="stylesheet" media="all" />
<script>
    jQuery(document).ready(function ($) {

        var _thisRowColClick = $("div.scexpdataoutput div.scexpdatarowitem.name, div.scexpdataoutput div.scexpdatarowitem.mdate, div.scexpdataoutput div.scexpdatarowitem.size");
        var _thisRowChkBxClick = $("div.scexpdataoutput div.scexpdatarowitem.check input.scexpitemcheckbox");
        var rowActiveClass = "active";
        var rowCheckboxField = ".scexpdatarowitem.check input.scexpitemcheckbox";

        // Uncheck all checkboxes on window load
        window.onload = function () {
            _thisRowColClick.parent().find(rowCheckboxField).prop("checked", false);
        }

        // Click event for row columns
        var clickColumn = _thisRowColClick.on("click", function (e) {

            e.stopPropagation();

            // Highlight and Check
            if (!$(this).parent().hasClass(rowActiveClass) && !$(this).parent().find(rowCheckboxField).hasClass(rowActiveClass)) {
                $(this).parent().addClass(rowActiveClass);
                $(this).parent().find(rowCheckboxField).addClass(rowActiveClass).prop("checked", true);

                return false;
            }

            // Unhighlight and Uncheck
            if ($(this).parent().hasClass(rowActiveClass) && $(this).parent().find(rowCheckboxField).hasClass(rowActiveClass)) {
                $(this).parent().removeClass(rowActiveClass);
                $(this).parent().find(rowCheckboxField).removeClass(rowActiveClass).prop("checked", false);

                return false;
            }
        });

        // Click event for row checkbox column
        var clickCheckbox = _thisRowChkBxClick.on("click", function (e) {

            e.stopPropagation();

            // Highlight and Check
            if (!$(this).parent().hasClass(rowActiveClass) && !$(this).parent().find(rowCheckboxField).hasClass(rowActiveClass)) {
                $(this).parent().parent().addClass(rowActiveClass);
                $(this).addClass(rowActiveClass).prop("checked", true);

                return false;
            }

            // Unhighlight and Uncheck
            if ($(this).parent().hasClass(rowActiveClass) && $(this).parent().find(rowCheckboxField).hasClass(rowActiveClass)) {
                $(this).parent().parent().removeClass(rowActiveClass);
                $(this).removeClass(rowActiveClass).prop("checked", false);

                return false;
            }
        });

        // Click event to select all rows
        $("#selectall").on("click", function () {

            if (!$(this).hasClass("active")) {
                $(this)
                        .addClass("active")
                        .prop("checked", true);
                $(this)
                        .closest("div.scexpoutputcol_inside")
                        .children("div.scexpdataoutput")
                        .find("div.scexpdatarow")
                        .addClass("active");
                $(this)
                        .closest("div.scexpoutputcol_inside")
                        .children("div.scexpdataoutput")
                        .find("div.scexpdatarowitem")
                        .addClass("active");
                $(this)
                        .closest("div.scexpoutputcol_inside")
                        .children("div.scexpdataoutput")
                        .find("input.scexpitemcheckbox")
                        .addClass("active")
                        .prop("checked", true);
            } else {
                $(this)
                        .removeClass("active")
                        .prop("checked", false);
                $(this)
                        .closest("div.scexpoutputcol_inside")
                        .children("div.scexpdataoutput")
                        .find("div.scexpdatarow")
                        .removeClass("active");
                $(this)
                        .closest("div.scexpoutputcol_inside")
                        .children("div.scexpdataoutput")
                        .find("div.scexpdatarowitem")
                        .removeClass("active");
                $(this)
                        .closest("div.scexpoutputcol_inside")
                        .children("div.scexpdataoutput")
                        .find("input.scexpitemcheckbox")
                        .removeClass("active")
                        .prop("checked", false);
            }
        });

        // Number of div.scexpdatarow Items in div.scexpdataoutput
        var fileRowNum = $("div.scexpdataoutput div.scexpdatarow").length;
        console.log(fileRowNum);
        $("div.scexpdataoutput div.scexpdatarow").each(function (index, value) {
            $(this).addClass("odd");
        });
        
        <?php if (isset($entity) && $entity) { ?>
        //Initialization for file browser
        initExplorer();
        <?php } ?>
    });

    // Add Odd or Even Class Name
    function addEvenOrOdd(numVal) {
        return (numVal % 2 == 0) ? " even" : " odd";
    }
    
    function initExplorer() {
        //var ws = $('').data();
    }
</script>

<?php /* File Explorer: Start */ ?>
<div class="scfileexplorer">
    <div class="scfileexplorer_inside">

        <?php /* File Explorer Header: Start */ ?>
        <div class="scexpheader">
            <div class="scexpheader_inside">

                <div class="scexpheadercol">
                    <div class="scexpheadercol_inside">
                        <h3>
                            <?php if (isset($entity) && $entity) { ?>
                                <span class="scexpoutputtitle">Files for</span>
                                <span class="scexpoutputwkspnme"><?php echo $entity['ws_name']; ?></span>
                            <?php } else { ?>
                                <span class="scexpoutputtitle">No selected workspace</span>
                            <?php } ?>
                        </h3>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>

                <?php /* Columns: Start */ ?>
                <div>

                    <?php /* Left Column: Start */ ?>
                    <div class="scexpheadercol left">
                        <div class="scexpheadercol_inside">

                            <ul class="filebreadcrumblist">
                                <?php if (isset($entity) && $entity) { ?>
                                    <li><a href="#"><?php echo $entity['ws_name']; ?></a></li>
                                    <li><span class="sep"><i class="fa fa-angle-right"></i></span></li>
                                    <li><a href="#">accounts</a></li>
                                    <li><span class="sep"><i class="fa fa-angle-right"></i></span></li>
                                    <li><a href="#">clients</a></li>
                                    <li><span class="sep"><i class="fa fa-angle-right"></i></span></li>
                                    <li><a href="#">js</a></li>
                                <?php } ?>
                            </ul>

                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>
                    <?php /* Left Column: End */ ?>

                    <?php /* Right Column: Start */ ?>
                    <div class="scexpheadercol right">
                        <div class="scexpheadercol_inside">
                            <form>
                                <?php /* Workspace Dropdown: Start */ ?>
                                <div class="scexpheaderdropdown">
                                    <label for="">Workspace:</label>
                                    <select id="" class="" name="ws" onchange="$(this).closest('form').submit();">
                                        <option value="">Please Select</option>
                                        <?php
                                        if (isset($entities) && count($entities) > 0) {
                                            foreach ($entities as $ws => $ety) {
                                                ?>
                                                <option value="<?php echo $ws; ?>" <?php echo isset($entity) && $entity && $entity['ws_directory'] == $ws ? 'selected="selected"' : '' ?>><?php echo $ety['ws_name']; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>

                                    <div class="clear"></div>
                                </div>
                                <?php /* Workspace Dropdown: End */ ?>

                                <?php /* Sort By Dropdown: Start */ ?>
                                <div class="scexpheaderdropdown">
                                    <label for="">Sort by:</label>
                                    <select id="" class="" name="sort">
                                        <option value="">Please Select</option>
                                        <option value="">Name</option>
                                        <option value="">Date Modified</option>
                                        <option value="">Date Created</option>
                                        <option value="">Size</option>
                                        <option value="">------------</option>
                                        <option value="">Ascending</option>
                                        <option value="">Descending</option>
                                    </select>

                                    <div class="clear"></div>
                                </div>
                                <?php /* Sort By Dropdown: End */ ?>
                            </form>
                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>
                    <?php /* Right Column: End */ ?>

                    <div class="clear"></div>
                </div>
                <?php /* Columns: End */ ?>

                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>
        <?php /* File Explorer Header: End */ ?>

        <?php /* File Explorer Output: Start */ ?>
        <div class="scexpoutput" data-workspace = "<?php echo isset($entity) && $entity ? json_encode($entity) : ''; ?>" data-dir="">
            <div class="scexpoutput_inside">

                <?php /* File List: Start */ ?>
                <div class="scexpoutputcol left">
                    <div class="scexpoutputcol_inside">

                        <?php /* Data Header: Start */ ?>
                        <div class="scexpdataheader">
                            <div class="scexpdatarow">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="selectall" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <span class="scexpitemtxt">Name</span>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">Date Modified</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">Size</span>
                                </div>
                            </div>

                            <div class="clear"></div>
                        </div>
                        <?php /* Data Header: End */ ?>

                        <?php /* Data Items: Start */ ?>
                        <div class="scexpdataoutput">

                            <div class="scexpdatarow folder">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx01" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">.idea</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/10/16</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">8.98KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow folder">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx02" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">Accounts</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/02/18</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">537KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow folder">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx03" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">ASSETS</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2013/11/24</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">1.02MB</span>
                                </div>
                            </div>
                            <div class="scexpdatarow folder">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx04" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">Art Illustration Examples</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/03/20</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">503KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow folder">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx05" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">Clients</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2013/09/21</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">12.0GB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow folder">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx06" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">css</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2013/11/24</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">20.6KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow folder">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx07" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">Documents</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2013/09/21</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">97.4MB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow folder">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx08" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">images</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2013/12/09</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">62.5KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow folder">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx09" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">includes</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/01</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">235KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow folder">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx10" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">js</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/11/24</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">194KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow document">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx11" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">.build</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/20</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">1KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow document">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx12" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">.htaccess</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/20</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">1KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow document">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx13" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">.project</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/20</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">1KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow html">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx14" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">index.html</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/20</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">2KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow php">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx15" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">index.php</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/20</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">107KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow text">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx16" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">license.txt</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/22</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">5KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow sql">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx17" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">project.sql</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/20</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">1.3MB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow html">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx18" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">readme.html</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/20</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">2KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow image">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx19" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">screenshot.png</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/20</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">256KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow css">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx20" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">stylesheet.css</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/20</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">23.1KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow php">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx21" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">wp-settings.php</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/22</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">29KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow php">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx22" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">wp-signup.php</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/19</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">2KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow php">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx23" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">wp-trackback.php</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/21</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">8KB</span>
                                </div>
                            </div>

                            <div class="scexpdatarow php">
                                <div class="scexpdatarowitem check">
                                    <input type="checkbox" id="chkbx24" class="scexpitemcheckbox" />
                                </div>
                                <div class="scexpdatarowitem name">
                                    <a href="#" class="scexpitemlink">
                                        <span class="scexpitemicon"></span>
                                        <span class="scexpitemtxt">xmlrpc.php</span>
                                    </a>
                                </div>
                                <div class="scexpdatarowitem mdate">
                                    <span class="scexpitemtxt">2014/01/21</span>
                                </div>
                                <div class="scexpdatarowitem size">
                                    <span class="scexpitemtxt">13KB</span>
                                </div>
                            </div>

                            <div class="clear"></div>
                        </div>
                        <?php /* Data Items: End */ ?>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* File List: End */ ?>

                <?php /* Right Column: Start */ ?>
                <div class="scexpoutputcol right">
                    <div class="scexpoutputcol_inside">

                        <?php /* Workspace Dropdown: Start * / ?>
                          <div class="scexpoutputdropdown">
                          <label for="">Workspace:</label>
                          <select id="" class="">
                          <option value="">Please Select</option>
                          <option value="">workspacename1</option>
                          <option value="">workspacename2</option>
                          <option value="">workspacename3</option>
                          <option value="">workspacename4</option>
                          <option value="">workspacename5</option>
                          </select>

                          <div class="clear"></div>
                          </div>
                          <?php / * Workspace Dropdown: End */ ?>

                        <?php /* Sort By Dropdown: Start * / ?>
                          <div class="scexpoutputdropdown">
                          <label for="">Sort by:</label>
                          <select id="" class="">
                          <option value="">Please Select</option>
                          <option value="">Name</option>
                          <option value="">Date Modified</option>
                          <option value="">Date Created</option>
                          <option value="">Size</option>
                          <option value="">------------</option>
                          <option value="">Ascending</option>
                          <option value="">Descending</option>
                          </select>

                          <div class="clear"></div>
                          </div>
                          <?php / * Sort By Dropdown: End */ ?>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* Right Column: End */ ?>

                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>
        <?php /* File Explorer Output: End */ ?>

        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>
<?php /* File Explorer: End */ ?>
