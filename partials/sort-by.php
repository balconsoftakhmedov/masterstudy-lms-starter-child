<div class="stm-sort-section">
    <span class="stm-filter-labels">
        <?php _e( 'Sort by', 'lms-starter-theme' ) ?>
    </span>

    <div class="stm-form-select__wrapper">
        <label aria-label="<?php esc_attr_e( 'Sort by', 'lms-starter-theme' ); ?>">
            <select name="sort_by" class="stm-form-select">
                <option value="relevance">
                    <?php _e( 'Relevancy', 'lms-starter-theme' ) ?>
                </option>
                <option value="title_asc">
                    <?php _e( 'Title Ascending', 'lms-starter-theme' ) ?>
                </option>
                <option value="title_desc">
                    <?php _e( 'Title Descending', 'lms-starter-theme' ) ?>
                </option>
                <option value="price_asc">
                    <?php _e( 'Cost Ascending', 'lms-starter-theme' ) ?>
                </option>
                <option value="price_desc">
                    <?php _e( 'Cost Descending', 'lms-starter-theme' ) ?>
                </option>
            </select>
        </label>
    </div>
</div>