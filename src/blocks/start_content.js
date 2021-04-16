const { registerBlockType } = wp.blocks;
const { ToggleControl, PanelBody, PanelRow } = wp.components;
const { InspectorControls } = wp.editor;


registerBlockType( "lightning-paywall/gutenberg-start-block", {
    title: 'LP Start Paid Text Content',
    icon: 'tagcloud',
    category: 'widgets',
    keywords: [ 'paywall', 'start-paywall' ],
    attributes: {
        pay_block:{
            type: 'boolean',
            default: true
        }
    },
    edit:props => {
        const {
            attributes: { pay_block },
            setAttributes
        } = props;
        return (
            <div>
            <hr class="lnpw_pay__gutenberg_block_separator"></hr>
                <InspectorControls>
                <PanelBody
                                title="LP Paywall Text"
                                initialOpen={true}>
                            <PanelRow>
                            <ToggleControl
                                label="Enable paywall"
                                checked={  pay_block }
                                onChange={ ( checked ) => {
                                    setAttributes( { pay_block: checked } );
                                }}
                                value={ pay_block } />
                            </PanelRow>
                            </PanelBody>
                    </InspectorControls>
            </div>)
        },
    save:props => {return null}
});