const { registerBlockType } = wp.blocks;


registerBlockType( "lightning-paywall/gutenberg-end-block", {
    title: 'LP End Paid Text Content',
    icon: 'tagcloud',
    category: 'widgets',
    keywords: [ 'paywall', 'end-paywall' ],
    edit:props =>{return <h2> LP End Paid Text Content </h2>},
    save:props => {return null}
});