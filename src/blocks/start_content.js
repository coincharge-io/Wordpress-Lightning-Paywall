const { registerBlockType } = wp.blocks;


registerBlockType( "lightning-paywall/gutenberg-start-block", {
    title: 'LP Start Paid Text Content',
    icon: 'tagcloud',
    category: 'widgets',
    keywords: [ 'paywall', 'start-paywall' ],
    edit:props => {return <h2>LP Start Paid Text Content</h2>},
    save:props => {return null}
});