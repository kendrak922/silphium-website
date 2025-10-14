<?php
/**
 * Enhanced ACF Color Picker with Named Colors
 */

function enhanced_acf_colorPicker_swatches()
{
    ?>
    <script type="text/javascript">
        (function($) {
            acf.add_filter('color_picker_args', function(args, $field) {
                // Enhanced color palette with organized sections
                args.palettes = [
                    // UNDERSTORY FOREST PALETTE
                    '#1B4332',  // Deep Forest
                    '#52B788',  // Forest Green
                    '#A3B18A',  // Sage Green
                    
                    // UNDERSTORY EARTH PALETTE  
                    '#D4A373',  // Warm Brown
                    '#8C6A4A',  // Earth Brown
                    '#E9C46A',  // Golden Amber
                    
                    // UNDERSTORY NEUTRAL PALETTE
                    '#FDF6EC',  // Soft Cream
                    '#2E2E2E',  // Charcoal
                    
                    // ORIGINAL THEME COLORS (Fallback)
                    '#F4E9DC',  // Cream
                    '#211B20',  // Dark
                    '#99D5C9',  // Light Teal
                    '#F7CCC4',  // Light Pink
                    '#FF8058',  // Orange
                    '#E8D94D',  // Yellow
                    '#BAAE11',  // Olive
                    '#54357B',  // Purple
                    '#3AC1A7',  // Teal
                    '#B997EF',  // Light Purple
                    '#F8C218',  // Gold
                ];
                
                // Add custom color picker options
                args.width = 275;
                args.height = 200;
                args.border = true;
                args.target = true;
                args.hide = true;
                args.hsv = true;
                args.clear = true;
                args.log = false;
                
                return args;
            });
        })(jQuery);
    </script>
    
    <!-- Add custom CSS for better color picker display -->
    <style>
        .acf-color-picker .iris-picker {
            border-radius: 8px !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
        }
        
        .acf-color-picker .iris-palette {
            border-radius: 4px !important;
            border: 2px solid #ddd !important;
            transition: all 0.2s ease !important;
        }
        
        .acf-color-picker .iris-palette:hover {
            border-color: #1B4332 !important;
            transform: scale(1.1) !important;
        }
        
        /* Add color name tooltips */
        .acf-color-picker .iris-palette[title="#1B4332"]::after {
            content: "Deep Forest";
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 10px;
            color: #666;
            white-space: nowrap;
        }
    </style>
    <?php
}
add_action('acf/input/admin_footer', 'enhanced_acf_colorPicker_swatches');