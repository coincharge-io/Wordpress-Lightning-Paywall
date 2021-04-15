const { registerBlockType } = wp.blocks;


registerBlockType( "lightning-paywall/gutenberg-end-video-block", {
    title: 'LP End Paid Video Content',
    icon: 'tagcloud',
    category: 'widgets',
    keywords: [ 'paywall', 'end-video-paywall' ],
    edit:props =>{return <h2> </h2>},
    save:props => {return null}
});