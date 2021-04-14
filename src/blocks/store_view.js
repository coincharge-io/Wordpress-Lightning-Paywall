const { registerBlockType } = wp.blocks;


registerBlockType( "lightning-paywall/gutenberg-store-view", {
    title: 'LP Video Store',
    icon: 'tagcloud',
    category: 'widgets',
    keywords: [ 'paywall', 'end-video-paywall' ],
    edit:props =>{return <h2> LP Video Store </h2>},
    save:props => {return null}
});