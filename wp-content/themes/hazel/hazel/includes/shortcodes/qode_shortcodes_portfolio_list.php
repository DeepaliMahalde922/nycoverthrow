<div id="qode_shortcode_form_wrapper">
    <form id="qode_shortcode_form" name="qode_shortcode_form" method="post" action="">
        <div class="input">
            <label>Type</label>
            <select name="type" id="type">
                <option value=""></option>
                <option value="standard">Standard</option>
                <option value="standard_no_space">Standard No Space</option>
                <option value="hover_text">Hover Text</option>
                <option value="hover_text_no_space">Hover Text No Space</option>
                <option value="masonry">Masonry Without Space</option>
                <option value="masonry_with_space">Masonry With Space</option>
            </select>
        </div>
        
        <div class="input">
            <label>Box Background Color</label>
            <div class="colorSelector"><div style=""></div></div>
            <input name="box_background_color" id="box_background_color" value="" maxlength="12" size="12" />
        </div>
           <div class="input">
            <label>Box Border</label>
            <select name="box_border" id="box_border">
                <option value="">Default</option>
                <option value="yes">yes</option>
                <option value="no">no</option>
            </select>
        </div>
            <div class="input">
            <label>Box Border Width</label>
            <input name="box_border_width" id="box_border_width" value="" size="5" />
        </div>
        <div class="input">
            <label>Box Border Color</label>
            <div class="colorSelector"><div style=""></div></div>
            <input type="text" name="box_border_color" id="box_border_color" value="" size="12" maxlength="12" />
        </div>
        
        <div class="input">
            <label>Filter</label>
            <select name="filter" id="filter">
                <option value=""></option>
                <option value="yes">yes</option>
                <option value="no">no</option>
            </select>
        </div>
        <div class="input">
            <label>Filter Color</label>
            <div class="colorSelector"><div style=""></div></div>
            <input type="text" name="filter_color" id="filter_color" value="" size="12" maxlength="12" />
        </div>
        <div class="input">
            <label>Lightbox</label>
            <select name="lightbox" id="lightbox">
                <option value=""></option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>
        <div class="input">
            <label>Columns</label>
            <select name="columns" id="columns">
                <option value=""></option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select>
        </div>
        <div class="input">
            <label>Image Size</label>
            <select name="image_size" id="image_size">
                <option value="">Default</option>
                <option value="full">Original Size</option>
                <option value="square">Square</option>
                <option value="landscape">Landscape</option>
                <option value="portrait">Portrait</option>
            </select>
        </div>
        <div class="input">
            <label>Order By</label>
            <select name="order_by" id="order_by">
                <option value=""></option>
                <option value="menu_order">Menu Order</option>
                <option value="title">Title</option>
                <option value="date">Date</option>
            </select>
        </div>
        <div class="input">
            <label>Order</label>
            <select name="order" id="order">
                <option value="ASC">ASC</option>
                <option value="DESC">DESC</option>
            </select>
        </div>
        <div class="input">
            <label>Number of portolios on page (-1 is all)</label>
            <input name="number" id="number" value="" size="5" />
        </div>
        <div class="input">
            <label>Category Slug (leave empty for all)</label>
            <input name="category" id="category" value="" size="5" />
        </div>
        <div class="input">
            <label>Selected Projects (leave empty for all, delimit by comma)</label>
            <input name="selected_projects" id="selected_projects" value="" size="40" />
        </div>
        <div class="input">
            <label>Show Load More</label>
            <select name="show_load_more" id="show_load_more">
                <option value=""></option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>
        <div class="input">
            <label>Title Tag</label>
            <select name="title_tag" id="title_tag">
                <option value=""></option>
                <option value="h2">h2</option>
                <option value="h3">h3</option>
                <option value="h4">h4</option>
                <option value="h5">h5</option>
                <option value="h6">h6</option>
            </select>
        </div>
        <div class="input">
            <label>Text Align</label>
            <select name="text_align" id="text_align">
               	<option value=""></option>
                <option value="left">Left</option>
                <option value="center">Center</option>
                <option value="right">Right</option>
            </select>
        </div>
        <div class="input">
            <input type="submit" name="Insert" id="qode_insert_shortcode_button" value="Submit" />
        </div>

    </form>
</div>
