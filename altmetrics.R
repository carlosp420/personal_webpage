 
out <- almplosallviews('10.1371/journal.pone.0039071', 
                       source_="counter", T, T, downform="json",
                       )

type = as.list(c("html", "pdf"))
almplotallviews(out, type)