const { registerBlockType } = wp.blocks;
const { InspectorControls, MediaUpload  } = wp.editor;
const { ToggleControl, PanelBody, PanelRow, TextareaControl, Button  } = wp.components;

registerBlockType( "lightning-paywall/gutenberg-start-video-block", {
    title: 'LP Start Paid Video Content',
    icon: 'tagcloud',
    category: 'widgets',
    keywords: [ 'paywall', 'start-video-paywall' ],
    attributes:{
        pay_view_block: {
            type: 'boolean',
            default: true
        },
        title: {
            type:'string',
            default: 'Untitled'
        },
        description: {
            type:'string',
            default: 'No description'
        },
        preview: {
            type:'string',
            default: ''
        }

    },
    edit:props => {
        const {
            attributes: { pay_view_block, title, description, preview },
            setAttributes
        } = props;
        
        return (
            <div>
            <hr class="lnpw_pay__gutenberg_block_separator"></hr>
                <InspectorControls>
					<PanelBody
						title="LP Paywall Video"
						initialOpen={true}>
					<PanelRow>
                        <ToggleControl
                        label="Enable payment block"
                        checked={  pay_view_block }
                        onChange={ ( checked ) => {
                            setAttributes( { pay_view_block: checked } );
                        }}
                        value={ pay_view_block } />
                    </PanelRow>
                    <PanelRow>
                        <TextareaControl 
                            label="Title"
                            help="Enter video title"
                            onChange={ ( content ) => {
                                setAttributes( { title: content } );
                            } }
                            value={ title }/>
                    </PanelRow>
                    <PanelRow>
                        <TextareaControl 
                        label="Description"
                        help="Enter video description"
                        onChange={ ( desc ) => {
                            setAttributes( { description: desc } );
                        }}
                        value={ description }/>
                    </PanelRow>
                    <PanelRow>
                        <MediaUpload
                            onSelect={( pic ) => {
                                setAttributes( { preview: pic.sizes.full.url } );
                            } }
                            
                            render={({ open }) =>(
                                <Button onClick={ open }>Video preview</Button>
                                )}/>
                    </PanelRow>
                    </PanelBody>
                </InspectorControls>
            </div>
        );},
    save:props => {
        return null;
    }
});