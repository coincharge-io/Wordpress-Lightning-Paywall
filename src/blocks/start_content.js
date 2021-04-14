const { registerBlockType } = wp.blocks;
const { ToggleControl } = wp.components;


registerBlockType( "lightning-paywall/gutenberg-start-block", {
    title: 'LP Start Paid Text Content',
    icon: 'tagcloud',
    category: 'widgets',
    keywords: [ 'paywall', 'start-paywall' ],
    attributes: {
        pay_block:{
            type: 'boolean',
            default: false
        }
    },
    edit:props => {
        const {
            attributes: { pay_block },
            setAttributes
        } = props;
        return (
        <div>
                    <ToggleControl
                    label="Enable paywall"
                    checked={  pay_block }
                    onChange={ ( checked ) => {
                        setAttributes( { pay_block: checked } );
                    } 
                    }
                    value={ pay_block } />
            </div>)
        },
    save:props => {return null}
});