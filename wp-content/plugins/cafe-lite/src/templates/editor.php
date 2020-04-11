<?php
/**
 * Templates for TemplateKit
 */
?>

<!-- Templatekit templates -->
<script type="text/html" id="tmpl-template-kit-content">
    <div class="cafe-tplkit-filters-list"></div>
    <div class="cafe-tplkit-templates-wrap">
    	<div class="cafe-tplkit-keywords-list"></div>
    	<div class="cafe-tplkit-templates-list"></div>
    </div>
</script>

<script type="text/html" id="tmpl-template-kit-error">
    <div class="elementor-library-error">
    	<div class="elementor-library-error-message"><?php
            esc_html_e('This template is available on Pro version only.', 'cafe-lite');
        ?></div>
    </div>
</script>

<script type="text/html" id="tmpl-template-kit-filters-item">
    <label class="cafe-templatekit-filter-label">
    	<input type="radio" value="{{ slug }}" <# if ( '' === slug ) { #> checked<# } #> name="cafe-tplkit-library-filter">
    	<span>{{ title }}</span>
    </label>
</script>

<script type="text/html" id="tmpl-template-kit-filters">
    <div id="cafe-templatekit-filters-container"></div>
</script>

<script type="text/html" id="tmpl-template-kit-header-back">
    <button type="button" class="cafe-templatekit-back">
    	<i class="dashicons dashicons-arrow-left-alt2"></i>
    	<?php esc_html_e('Back to Library', 'cafe-lite'); ?>
    </button>
</script>

<script type="text/html" id="tmpl-template-kit-header">
    <div id="cafe-templatekit-header-tabs"></div>
    <div id="cafe-templatekit-header-actions"></div>
    <div id="cafe-templatekit-header-close-modal" class="elementor-template-library-header-item" title="<?php esc_html_e('Close', 'cafe-lite'); ?>">
    	<i class="eicon-close" title="Close"></i>
    </div>
</script>

<script type="text/html" id="tmpl-template-kit-insert-button">
    <# if ( TemplateKitConf.license.activated ) { #>
    <button class="elementor-template-library-template-action cafe-templatekit-template-insert elementor-button elementor-button-success">
    	<i class="eicon-file-download"></i>
        <span class="elementor-button-title"><?php esc_html_e('Insert', 'cafe-lite'); ?></span>
    </button>
    <# } else { #>
        <a class="elementor-template-library-template-action elementor-button elementor-button-go-pro" href="{{ TemplateKitConf.license.link }}" title="<?php esc_html_e('This template is available on Pro version only!', 'cafe-lite') ?>" target="_blank">
            <i class="eicon-external-link-square" aria-hidden="true"></i>
            <?php esc_html_e('Go Pro', 'cafe-lite') ?>
        </a>
    <# } #>
</script>

<script type="text/html" id="tmpl-template-kit-item">
    <# var activeTab = window.TemplateKitConf.tabs[ type ]; #>
    <div class="elementor-template-library-template-body">
    	<# if ( 'local' !== source ) { #>
    	<div class="elementor-template-library-template-screenshot">
    		<# if ( 'local' !== source ) { #>
    		<div class="elementor-template-library-template-preview">
    			<i class="fa fa-search-plus"></i>
    		</div>
    		<# } #>
    		<img src="{{ thumbnail }}" alt="">
    	</div>
    	<# } #>
    </div>
    <div class="elementor-template-library-template-footer">
        <# if ( TemplateKitConf.license.activated ) { #>
        <button class="elementor-template-library-template-action cafe-templatekit-template-insert elementor-button elementor-button-success">
        	<i class="eicon-file-download"></i>
            <span class="elementor-button-title"><?php esc_html_e('Insert', 'cafe-lite'); ?></span>
        </button>
        <# } else { #>
            <a class="elementor-template-library-template-action elementor-button elementor-button-go-pro" href="{{ TemplateKitConf.license.link }}" title="<?php esc_html_e('This template is available on Pro version only!', 'cafe-lite') ?>" target="_blank">
                <i class="eicon-external-link-square" aria-hidden="true"></i>
                <?php esc_html_e('Go Pro', 'cafe-lite') ?>
            </a>
        <# } #>
    </div>
    <# if ( 'local' === source || true == activeTab.settings.show_title ) { #>
    <div class="elementor-template-library-template-name">{{{ title }}}</div>
    <# } else { #>
    <div class="elementor-template-library-template-name-holder"></div>
    <# } #>
    <# if ( 'local' === source ) { #>
    <div class="elementor-template-library-template-type">
    	<?php esc_html_e('Type:', 'cafe-lite'); ?> {{{ typeLabel }}}
    </div>
    <# } #>
</script>

<script type="text/html" id="tmpl-template-kit-keywords">
    <#
    	if ( ! _.isEmpty( keywords ) ) {
    #>
    <select class="cafe-tplkit-library-keywords">
    	<option value=""><?php esc_html_e('Any Topic', 'cafe-lite'); ?></option>
    	<# _.each( keywords, function( title, slug ) { #>
    	<option value="{{ slug }}">{{ title }}</option>
    	<# } ); #>
    </select>
    <#
    	}
    #>
</script>

<script type="text/html" id="tmpl-template-kit-loading">
    <div class="elementor-loader-wrapper">
    	<div class="elementor-loader">
    		<div class="elementor-loader-box"></div>
    		<div class="elementor-loader-box"></div>
    		<div class="elementor-loader-box"></div>
    		<div class="elementor-loader-box"></div>
    	</div>
    	<div class="elementor-loading-title">Loading...</div>
    </div>
</script>

<script type="text/html" id="tmpl-template-kit-preview">
    <img class="cafe-tplkit-template-preview-img">
</script>

<script type="text/html" id="tmpl-template-kit-tabs-item">
    <label>
    	<input type="radio" value="{{ slug }}" name="cafe-tplkit-library-tab">
    	<span>{{ title }}</span>
    </label>
</script>

<script type="text/html" id="tmpl-template-kit-tabs">
    <div id="cafe-templatekit-tabs-items"></div>
</script>

<script type="text/html" id="tmpl-template-kit-templates">
    <div id="cafe-templatekit-templates-container"></div>
</script>

<script type="text/html" id="tmpl-template-kit-add-button">
    <div class="add-cafe-tplkit-template elementor-add-section-area-button">
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve">
<g>
<g>
<path
    d="M502,206.5h-82v-21.167c0-5.523-4.478-10-10-10H194c-5.522,0-10,4.477-10,10c0,5.523,4.478,10,10,10h206v76.666
c0,67.348-35.957,129.942-94.018,164H114.018C55.957,401.942,20,339.347,20,272v-76.666h67c5.522,0,10-4.477,10-10
c0-5.523-4.478-10-10-10H10c-5.522,0-10,4.477-10,10V272c0,37.8,10.16,74.864,29.382,107.183
C42.345,400.98,59.165,420.228,78.862,436H10c-5.522,0-10,4.477-10,10c0,36.393,29.607,66,66,66h312c36.393,0,66-29.607,66-66
c0-5.523-4.478-10-10-10h-92.862c18.129-14.516,33.812-31.983,46.302-51.667h28.227C468.785,384.333,512,341.118,512,288v-71.5
C512,210.977,507.523,206.5,502,206.5z M422.905,456c-4.578,20.572-22.974,36-44.905,36H66c-21.931,0-40.326-15.428-44.905-36
h90.244h197.322H422.905z M420,272v-5.5h32V288c0,20.034-16.299,36.333-36.333,36.333h-2.271C417.761,307.327,420,289.745,420,272
z M492,288c0,42.09-34.243,76.333-76.333,76.333h-17.02c3.193-6.521,6.045-13.197,8.537-20h8.483
C446.73,344.333,472,319.062,472,288v-31.5c0-5.523-4.478-10-10-10h-42v-20h72V288z"/>
</g>
</g>
<g>
<g>
<path
    d="M219.549,55.343l-5.203-6.533c-7.638-9.588-7.649-22.991-0.027-32.593c3.434-4.326,2.71-10.616-1.616-14.049
c-4.327-3.435-10.616-2.71-14.05,1.616c-13.442,16.936-13.422,40.575,0.049,57.487l5.203,6.533
c9.981,12.53,9.996,30.045,0.036,42.594l-5.332,6.718c-3.434,4.326-2.71,10.616,1.616,14.049c1.839,1.459,4.032,2.168,6.21,2.168
c2.946,0,5.865-1.296,7.84-3.784l5.332-6.718C235.387,102.949,235.364,75.197,219.549,55.343z"/>
</g>
</g>
<g>
<g>
<path
    d="M274.978,77.827l-5.203-6.532c-7.638-9.589-7.649-22.992-0.027-32.594c3.434-4.326,2.71-10.616-1.616-14.049
c-4.327-3.435-10.616-2.71-14.05,1.616c-13.442,16.936-13.422,40.575,0.049,57.488l5.203,6.533
c9.98,12.531,9.995,30.047,0.035,42.595l-5.331,6.717c-3.434,4.326-2.71,10.616,1.616,14.049c1.839,1.459,4.032,2.167,6.21,2.167
c2.946,0,5.866-1.297,7.84-3.784l5.33-6.717C290.815,125.435,290.792,97.683,274.978,77.827z"/>
</g>
</g>
<g>
<g>
<path
    d="M164.12,77.828l-5.203-6.532c-7.638-9.588-7.649-22.992-0.028-32.594c3.434-4.326,2.71-10.616-1.616-14.05
c-4.327-3.434-10.616-2.708-14.05,1.616c-13.441,16.936-13.421,40.576,0.05,57.488l5.203,6.533
c9.98,12.531,9.995,30.047,0.036,42.594l-5.332,6.717c-3.434,4.326-2.711,10.616,1.615,14.05c1.839,1.459,4.031,2.168,6.21,2.168
c2.946,0,5.865-1.296,7.839-3.783l5.333-6.718C179.957,125.436,179.934,97.684,164.12,77.828z"/>
</g>
</g>
<g>
<g>
<path
    d="M150.28,178.26c-1.86-1.86-4.44-2.93-7.07-2.93s-5.21,1.07-7.07,2.93c-1.859,1.87-2.93,4.44-2.93,7.07
s1.07,5.21,2.93,7.08c1.86,1.86,4.44,2.92,7.07,2.92s5.21-1.06,7.07-2.92c1.869-1.87,2.93-4.44,2.93-7.08
C153.21,182.7,152.149,180.12,150.28,178.26z"/>
</g>
</g>
<g>
<g>
<path
    d="M362,262c-5.522,0-10,4.477-10,10c0,37.119-15.056,73.598-41.308,100.083c-3.888,3.922-3.859,10.254,0.063,14.142
c1.95,1.933,4.495,2.898,7.04,2.898c2.574-0.001,5.148-0.989,7.103-2.962C354.832,355.961,372,314.351,372,272
C372,266.477,367.522,262,362,262z"/>
</g>
</g>
<g>
<g>
<path
    d="M293.885,399.225c-2.714-4.811-8.813-6.51-13.623-3.797l-0.228,0.129c-4.811,2.714-6.51,8.813-3.797,13.623
c1.838,3.258,5.227,5.089,8.719,5.089c1.664,0,3.352-0.417,4.904-1.292l0.228-0.129
C294.899,410.134,296.598,404.035,293.885,399.225z"/>
</g>
</g>
</svg>
</div>
</script>
<!-- /TemplateKit templates -->
