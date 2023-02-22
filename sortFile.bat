#!/bin/bash
cd data
sort movies.txt > temp.txt
del movies.txt
ren temp.txt movies.txt
cd ..