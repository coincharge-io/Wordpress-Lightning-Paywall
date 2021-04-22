const { registerBlockType  } = wp.blocks;
const { ToggleControl, PanelBody, PanelRow, SelectControl, __experimentalNumberControl: NumberControl  } = wp.components;
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
        },
        currency:{
            type: 'string',
        },
        price:{
            type: 'number'
        },
        duration_type:{
            type: 'string',
        },
        duration:{
            type: 'number'
        }
    },
    edit:props => {
        const {
            attributes: { pay_block, currency, duration_type, price, duration },
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
                            <PanelRow>
                                <SelectControl 
                                label="Currency"
                            value={ currency } 
                            onChange={ (  selectedItem  ) => setAttributes( {currency:selectedItem} )}
                            options={ [
                                { value: '', label: 'Default' },
                                { value: 'SATS', label: 'SATS' },
                                { value: 'BTC', label: 'BTC' },
                                { value: 'EUR', label: 'EUR' },
                                { value: 'USD', label: 'USD' },
                            ] }/>
                            </PanelRow>
                            <PanelRow>
                            <NumberControl
                                    label="Price"
                                    value={ price }
                                    onChange={ ( nextValue ) => setAttributes( {price:Number(nextValue)} ) }
                                />
                            </PanelRow>
                            <PanelRow>
                                <SelectControl 
                                label="Duration type"
                            value={ duration_type } 
                            onChange={ (  selectedItem  ) => setAttributes( {duration_type:selectedItem} )}
                            options={ [
                                { value: '', label: 'Default' },
                                { value: 'minute', label: 'Minute' },
                                { value: 'hour', label: 'Hour' },
                                { value: 'week', label: 'Week' },
                                { value: 'month', label: 'Month' },
                                { value: 'year', label: 'Year' },
                                { value: 'onetime', label: 'Onetime' },
                                { value: 'unlimited', label: 'Unlimited' },
                            ] }/>
                            </PanelRow>
                            <PanelRow>
                            <NumberControl
                                    label="Duration"
                                    value={ duration }
                                    onChange={ ( nextValue ) => setAttributes( {duration:Number(nextValue)} ) }
                                />
                            </PanelRow>
                            </PanelBody>
                    </InspectorControls>
            </div>)
        },
    save:props => {return null}
});
