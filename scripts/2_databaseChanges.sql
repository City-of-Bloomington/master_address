-- We want to start using placeTypes on places
alter table places add placeType_id int unsigned after class;
alter table places add foreign key (placeType_id) references placeTypes(id);

update places,placeTypes set placeType_id=placeTypes.id where class=type;
