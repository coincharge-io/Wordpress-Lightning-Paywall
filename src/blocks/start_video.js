const { registerBlockType } = wp.blocks;
const { RichText, MediaUpload  } = wp.editor;
const { ToggleControl } = wp.components;


registerBlockType( "lightning-paywall/gutenberg-start-video-block", {
    title: 'LP Start Paid Video Content',
    icon: 'tagcloud',
    category: 'widgets',
    keywords: [ 'paywall', 'start-video-paywall' ],
    attributes:{
        pay_view_block: {
            type: 'boolean',
            default: false
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
                <div>
                    <ToggleControl
                    label="Enable paywall"
                    checked={  pay_view_block }
                    onChange={ ( checked ) => {
                        setAttributes( { pay_view_block: checked } );
                    } 
                    }
                    value={ pay_view_block } />
                </div>
                <div>
                <RichText
                    onChange={ ( content ) => {
                        setAttributes( { title: content } );
                    } }
                    value={ title }
                />
                </div>
                <div>
                <RichText
                    onChange={ ( desc ) => {
                        setAttributes( { description: desc } );
                    } 
                    }
                    value={ description }
                />
                </div>
                <div>
                <strong>Select a preview image:</strong>
                <MediaUpload
                    onSelect={( pic ) => {
                        setAttributes( { preview: pic.sizes.full.url } );
                    } }
                    render={({ open }) => (
                    <button onClick={open}>
                        Upload Image!
                    </button>
                    )}
                />
                </div>
            </div>
        );},
    save:props => {
        return null;
    }
});