---
layout: post
title: "Plotting ages of phylogenetic trees in R"
description: ""
excerpt: "It appears that different genes might estimate very different age 
    estimates for your phylogenetic trees. This seems to be the case with the
    COI gene that tends to pull your timings towards the past. Saturation in
    the 3rd codon position might be accused for responsibility  (you can
    inspect the saturation level with..."
category: "R"
tags: [R, dating, phylogenetic tree, plots, source code, timing]
---
{% include JB/setup %}

It appears that different genes might estimate very different age estimates for
your phylogenetic trees. This seems to be the case with the COI gene that tends
to pull your timings towards the past. Saturation in the 3rd codon position
might be accused for responsibility  (you can inspect the saturation level with
some cool plots).

Brandley et al. (2011) use a nice way to figure out whether some of your genes
might be giving very different time estimates for your trees. You can select 
1000 random trees from your BEAST run and plot the distributions of the ages 
for the crown group of different genes, different codon positions and the 
combined analyses.

Something like this plot consisting on a simulation of a gen1 estimating a 
crown age of 30Mya, gen2 estimating an age of 50Mya and the combined analysis 
giving an age of 40Mya.

![center](/figs/1.png) 

This can be done in the ubiquitous statistical software R, and here is the code: [http://dx.doi.org/10.6084/m9.figshare.96636](http://dx.doi.org/10.6084/m9.figshare.96636)

{% highlight r %}
library("ape")
library("Hmisc")
library("TreeSim")

# simulate three sets of trees with different ages you will skip this step
# and use your own tree files instead
trees_gen1 <- sim.bd.taxa.age(n = 50, numbsim = 100, lambda = 0.03, mu = 0.001, 
    age = 30)
for (i in 1:length(trees_gen1)) {
    write.tree(trees_gen1[[i]], file = "trees_gen1.nwk", append = TRUE)
}

trees_gen2 <- sim.bd.taxa.age(n = 50, numbsim = 100, lambda = 0.03, mu = 0.001, 
    age = 50)
for (i in 1:length(trees_gen2)) {
    write.tree(trees_gen2[[i]], file = "trees_gen2.nwk", append = TRUE)
}

trees_combined <- sim.bd.taxa.age(n = 50, numbsim = 100, lambda = 0.03, mu = 0.001, 
    age = 40)
for (i in 1:length(trees_combined)) {
    write.tree(trees_combined[[i]], file = "trees_combined.nwk", append = TRUE)
}

# you might want to plot the ages for an ingroup or a particular clade
# then you might want to remove certain taxa or the outgroups use this
# variable and replace with your real tip names
outgroup_tips <- c()

## --- Function to get distribution of ages for root
## from 1000 tree file
get_crown_age_distribution <- function(file, outgroup_tips) {
    tips <- outgroup_tips
    phys <- read.tree(file)
    
    # get the branching times for the crown
    branching_times <- c()
    for (i in 1:length(phys)) {
        phy <- phys[[i]]
        if (length(tips) > 0) {
            phy <- drop.tip(phys[[i]], tips)
        }
        
        # the the node number for the root
        nodes <- length(phy$tip.label) + 1
        
        x <- branching.times(phy)
        
        # get the branching time for the root
        branching_times <- c(branching_times, x[names(x) == nodes])
    }
    return(branching_times)
}


## get data
gen1 <- get_crown_age_distribution(file = "trees_gen1.nwk", outgroup_tips = outgroup_tips)
gen2 <- get_crown_age_distribution(file = "trees_gen2.nwk", outgroup_tips = outgroup_tips)
combined <- get_crown_age_distribution(file = "trees_combined.nwk", outgroup_tips = outgroup_tips)

hist(gen1, freq = FALSE, axes = F, xlim = c(80, 0), border = "white", main = "Age posterior probability distributions\nof simulated trees", 
    ylab = "", xlab = "Million years ago")
axis(4, at = seq(0, 0.6, 0.1), las = 1, font = 2)
axis(1, font = 2)

lines(density(combined), lwd = 2, col = "black")
lines(density(gen1), lwd = 2, col = "red")
lines(density(gen2), lwd = 2, col = "blue")

minor.tick(ny = 1)

legend.txt <- c("Data combined", "gen1", "gen2")
legend.colors <- c("black", "red", "blue")
legend(cex = 0.8, "topleft", legend.txt, pch = 22, lwd = 0, pt.bg = legend.colors, 
    title = "Locus", pt.cex = 2)
{% endhighlight %}

{% cite brandley2011 %} 

#### References
{% bibliography --cited %}
