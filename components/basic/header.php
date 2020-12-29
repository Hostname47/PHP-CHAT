
<?php
    use classes\Config;
?>
<header>
    <div id="top-header">
        <div id="header-logo-container">
            <!-- height of <a> should be like img height -->
            <a href="" style="height: 28px"><img src="<?php echo Config::get("root/path");?>assets/images/logos/large.png" alt="Logo" id="header-logo"></a>
        </div>
        <div class="inline-logo-separator">〡</div>
        <div class="row-v-flex">
            <form action="" method="post" id="header-search-form">
                <input type="text" name="resource-search-field" class="input-text-style-1 search-back" placeholder="Search for friends, posts, events ..">
                <input type="submit" value="search" name="resource-search-button" class="search-button-style-1">
            </form>
        </div>
        <div id="global-header-strip-container">
            <div class="row-v-flex header-menu">
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="horizontal-menu-link menu-button-style-1" id="home-button">Home</a>
                    <div class="sub-options-container">
                        
                    </div>
                </div>
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="horizontal-menu-link menu-button-style-1" id="explore-button">Explore</a>
                    <div class="sub-options-container">

                    </div>
                </div>
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="horizontal-menu-link menu-button-style-1" id="live-button">Live</a>
                    <div class="sub-options-container">

                    </div>
                </div>
            </div>
            <div class="menu-items-separator">〡</div>
            <div class="row-v-flex header-menu">
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="menu-button-style-2" id="notification-button"></a>
                    <div class="sub-options-container">
                        
                    </div>
                </div>
                <div class="horizontal-menu-item-wrapper">
                    <a href="" class="menu-button-style-2" id="messages-button"></a>
                    <div class="sub-options-container">

                    </div>
                </div>
            </div>
            <div class="menu-items-separator">〡</div>
            <div class="row-v-flex header-menu">
                <div class="horizontal-menu-item-wrapper">
                    <a href="" id="user-photo-button"></a>
                    <div class="sub-options-container">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
<header>